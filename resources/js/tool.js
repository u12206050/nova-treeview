Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'tree-view',
      path: '/tree-view',
      component: require('./components/Tool.vue'),
    },
  ])
})
