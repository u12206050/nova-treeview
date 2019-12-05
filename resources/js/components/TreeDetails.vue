<template>
    <div class="ml-6 w-full" v-if="node">
        <div class="flex items-center mb-3">
            <h1 class="flex-no-shrink text-90 font-normal text-2xl">Details</h1>
            <div class="ml-3 w-full flex items-center"></div>
            <div class="flex w-full justify-end items-center">
                <button @click="showModal = true" title="Delete" class="btn btn-default btn-icon btn-white mr-3"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current text-80"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg></button>
                <router-link :to="`resources/${resource}/${node.id}/edit`" class="btn btn-default btn-icon bg-primary" data-testid="edit-resource" dusk="edit-resource-button" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current text-white" style="margin-top: -2px; margin-left: 3px;"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg></router-link>
            </div>
        </div>
        <div class="card mb-6 py-3 px-6">
            <div class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Id</h4>
                </div>
                <div class="w-3/4 py-4 break-words">
                    <p class="text-90">{{ node.id }}</p>
                </div>
            </div>
            <div class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Title</h4>
                </div>
                <div class="w-3/4 py-4 break-words">
                    <p class="text-90">{{ node.text }}</p>
                </div>
            </div>
            <div class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Order</h4>
                </div>
                <div class="w-3/4 py-4 break-words">
                    <p class="text-90">{{ node.order }}</p>
                </div>
            </div>
            <div class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Parent</h4>
                </div>
                <div class="w-3/4 py-4 break-words">
                    <p class="text-90">{{ node.parentText }}</p>
                </div>
            </div>
            <div class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Active</h4>
                </div>
                <div class="w-3/4 py-4 break-words">
                    <checkbox
                        class="mt-2"
                        @input="$emit('onToggle')"
                        :id="is_active"
                        :name="Active"
                        :checked="node.is_active"
                        :disabled="node.id === 1 || typeof node.is_active === 'string'"
                    />
                </div>
            </div>
        </div>
        <delete-resource-modal
            v-if="showModal"
            @confirm="confirmDelete"
            @close="showModal = false"
            mode="delete"
        />
    </div>
</template>

<script>
export default {
    props: [ 'node', 'resource' ],
    data() {
        return {
            showModal: false
        }
    },
    methods: {
        confirmDelete() {
            this.showModal = false;
            if(this.node.children.length != 0) {
                alert("Can't delete resource if there are sub resources")
            } else {
                this.$emit('onDelete')
            }
        }
    }
}
</script>