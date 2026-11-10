const equipoRoutes = [
    {
        path: '/equipos',
        name: 'spa.equipo',
        component: () => import('../../views/equipos/EquipoIndex.vue'),
    },
    {
        path: 'equipos/crear',
        name: 'spa.equipo.register',
        component: () => import('../../views/equipos/EquipoCreate.vue'),
    },
    {
        path: 'equipos/editar/:id',
        name: 'spa.equipo.edit',
        component: () => import('../../views/equipos/EquipoEdit.vue'),
    },
];

export default equipoRoutes;