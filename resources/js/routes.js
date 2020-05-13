const routes = [
  { path: '/dashboard', props:{etapa:'dashboard'}, name:'Dashboard', component: require('./Dashboard/WelcomeComponent.vue').default },
  { path: '/streaming/salas', props:{etapa:'streaming'},name:'Streaming',component: require('./streaming/SalaStreaming.vue').default },
  { path: '/streaming/online/:id', props:{etapa:'streaming'},name:'Streaming',component: require('./streaming/OnlineStreaming.vue').default },
  ];
  export default routes;
  