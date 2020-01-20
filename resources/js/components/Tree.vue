<template>
    <loading-view :loading="loading">
        <div class="flex">
                <v-jstree
                    :data="tree"
                    :draggable="true"
                    whole-row
                    @item-drop="dragEnded"
                    @item-click="itemClick"
                ></v-jstree>
                <TreeDetails v-if="currentNode" :resource="resource" :node="currentNode" @onOrder="orderNode" @onDelete="deleteNode" @onToggle="toggleNode" />
        </div>
    </loading-view>
</template>

<script>
import TreeDetails from './TreeDetails'
import VJstree from 'vue-jstree'
export default {
    props: [ 'resource' ],
    data() {
        return {
            loading: true,
            tree: {},
            parents: {},
            lastRetrievedAt: 0,
            currentNode: null
        }
    },
    methods: {
        parseResources(rows) {
            const tree = {}
            const map = {}
            if (!rows) return
            rows.forEach(row => {
                    let leaf = map[row.id.value] = {
                        id: row.id.value,
                        text: this.attrValue(row, 'title'),
                        is_active: this.attrValue(row, 'is_active'),
                        icon: this.isActiveIcon(true, true),
                        order: this.attrValue(row, 'order'),
                        slug: this.attrValue(row, 'slug'),
                        parentText: this.attrValue(row, 'parent'),
                        parentId: this.attrValue(row, 'parent', 'belongsToId'),
                        children: [],
                        opened: true,
                    }
                });
            rows.forEach(row => {
                let leaf = map[row.id.value]
                if (leaf.parentId) {
                    if (map[leaf.parentId]) {
                        let parent = map[leaf.parentId]
                        if(!parent.children) parent.children = [leaf]
                        else parent.children.push(leaf)
                        this.order(parent.children)
                    }
                } else tree[row.id.value] = leaf
            })
            this.parents = map
            this.tree = tree
            Object.values(tree).forEach(root => this.setIconState(root.children, root.is_active))
        },
        attrValue(row, attr, prop = 'value') {
            const field = row.fields.find(f => f.attribute === attr)
            return field && field[prop]
        },
        itemClick (node) {
            this.currentNode = node.model
        },
        dragEnded(node, item, draggedItem, e) {
            console.log(node, item, draggedItem, e)
            this.updateParent(draggedItem, item)
        },
        formData(node, fields, method) {
            const formData = new FormData()
            const nodeFields = {
                order: node.order,
                title: node.text,
                slug: node.slug,
                ...fields
            }
            for (let attr in nodeFields) {
                formData.append(attr, nodeFields[attr])
            }
            formData.append('_method', method)
            formData.append('_retrieved_at', this.lastRetrievedAt)
            return formData
        },
        updateNode(node, fields = {}) {
            return Nova.request().post(`/nova-api/${this.resource}/${node.id}?editing=true&editMode=update`, this.formData(node, fields, "PUT")).then(res => {
                this.mergeResponse(res.data)
            })
        },
        updateParent(node, newParent) {
            const oldParent = node.parentId && this.parents[node.parentId]
            return this.updateNode(node, {
                order: 0,
                parent: newParent.id
            }).catch(err => {
                newParent.children.splice(newParent.children.indexOf(node), 1)
                if (oldParent) {
                    oldParent.children.push(node)
                    this.order(oldParent.children)
                }
            })
        },
        fetchData() {
            this.loading = true
            Nova.request().get(`/nova-api/${this.resource}`)
                .then(res => {
                    this.currentNode = null
                    this.parseResources(res.data.resources)
                    this.updateRetrievedAt()
                }).catch(err => {
                    console.error(err)
                    this.$router.replace('/404')
                }).then(() => this.loading = false);
        },
        setIconState(children = [], parentActive = true) {
            Array.isArray(children) && children.forEach(child => {
                child.icon = this.isActiveIcon(child.is_active, parentActive)
                this.setIconState(child.children, child.icon === 'enabled')
            })
        },
        isActiveIcon(active, parentActive) {
            // THIS WORKS; DONT TOUCH!
            return !active ? 'disabled' : parentActive ? 'enabled' : 'parentDisabled'
        },
        order(nodes) {
            nodes.sort((a,b) => a.order > b.order ? 1 : -1)
        },
        updateRetrievedAt() {
            this.lastRetrievedAt = Math.floor(Date.now() / 1000)
        },
        deleteNode() {
            Nova.request().post(`/nova-api/${this.resource}`, this.formData(this.currentNode, {
                resources: this.currentNode.id,
            }, "DELETE"))
                .then(res => {
                    let parent = this.parents[this.currentNode.parentId]
                    parent.children.splice(parent.children.indexOf(this.currentNode), 1)
                    this.currentNode = null
                })
                .catch()
        },
        toggleNode() {
            const node = this.currentNode
            const activeState = node.is_active
            node.is_active = 'loading'

            this.updateNode(node, {
                is_active: !activeState ? 1 : 0
            }).catch(err => {
                console.error(err)
                node.is_active = activeState
                alert('Failed toggling resource')
            })
        },
        orderNode(dir) {
            const node = this.currentNode
            let order = ((node.order*1) || 0) + dir
            if (order < 0) order = 0
            if (node.parentId && this.parents[node.parentId].children) {
                const cLen = this.parents[node.parentId].children.length
                if (order > cLen)
                    order = cLen
            }
            this.updateNode(node, {
                order
            }).catch(err => {
                alert('Failed changing order')
            })
        },
        mergeResponse(res) {
            if (res.id && res.resource) {
                const { id, parent_id, title, slug, order, is_active} = res.resource
                const node = this.parents[id]
                const parent = this.parents[parent_id]
                node.parentText = parent.text
                node.parentId = parent_id
                node.text = title
                node.is_active = is_active
                node.icon = this.isActiveIcon(is_active, parent.icon === 'enabled')
                node.slug = slug
                node.order = order
                this.order(parent.children)
                this.setIconState(node.children, node.icon === 'enabled')
                this.updateRetrievedAt()
            }
        }
    },
    watch: {
        resource: {
            immediate: true,
            handler() {
                this.fetchData()
            }
        }
    },
    components: {
        VJstree,
        TreeDetails
    }
}
</script>

<style lang="scss">
button:focus {
    outline: none;
}
.tree-default .tree-themeicon-custom {
    border: none;
    border-radius: 100px;
    box-shadow: 0 0 0 4px white inset;
    &.enabled {
        background: green;
    }
    &.disabled {
        background: red;
    }
    &.parentDisabled {
        background: orange;
    }
}
</style>