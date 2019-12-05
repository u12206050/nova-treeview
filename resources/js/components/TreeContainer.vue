<template>
    <div>
        <h1 class="mb-3 text-90 font-normal text-2xl capitalize">{{resource}}</h1>
        <div class="flex" style=""><div class="relative h-9 flex-no-shrink mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="search" role="presentation" class="fill-current absolute search-icon-center ml-3 text-70">
                <path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
            </svg> 
            <input data-testid="search-input" dusk="search" placeholder="Search" type="search" class="appearance-none form-search w-search pl-search shadow">
            </div> 
            <div class="w-full flex items-center mb-6">
                <div class="flex w-full justify-end items-center mx-3">
                </div> 
                <div class="flex-no-shrink ml-auto">
                    <router-link :to="`/resources/${resource}/new?viaResource=&viaResourceId=&iaRelationship=`" class="capitalize btn btn-default btn-primary" dusk="create-button">
                        Create {{resource}}
                    </router-link>
                </div>
            </div>
        </div>
        <Tree :resource="resource"/>
    </div>
</template>

<script>
import Tree from './Tree'
export default {
    props: [ 'resource' ],
    data() {
        return {
            showModal: false
        }
    },
    components: {
        Tree
    },
    methods: {
        handleConfirm() {
            this.showModal = false;
            alert("Deleted!")
        },
        handleClose() {
            this.showModal = false;
        }
    }
}
</script>