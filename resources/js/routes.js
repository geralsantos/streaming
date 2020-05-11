const routes = [
  { path: '/dashboard', props:{etapa:'dashboard'}, name:'Dashboard', component: require('./Dashboard/WelcomeComponent.vue').default },
  { path: '/streaming/',props:{etapa:'streaming'}, name:'Streaming', component: require('./Streaming/SalaStreaming.vue').default },

  ];
  export default routes;
  