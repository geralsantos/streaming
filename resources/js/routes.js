const routes = [
  { path: '/dashboard', name:'Dashboard', component: require('./Dashboard/WelcomeComponent.vue').default },
  { path: '/streaming/:id', name:'Streaming', component: require('./Streaming/SalaStreaming.vue').default },

  ];
  export default routes;
  