const mantenimientoRoutes = [
    {
        path: 'mantenimientos',
        name: 'spa.mantenimiento',
        component: () => import('../../views/mantenimientos/MantenimientoIndex.vue'),
    },
    {
        path: 'mantenimientos/crear',
        name: 'spa.mantenimiento.register',
        component: () => import('../../views/mantenimientos/MantenimientoCreate.vue'),
    },
    {
        path: 'mantenimientos/editar/:id',
        name: 'spa.mantenimiento.edit',
        component: () => import('../../views/mantenimientos/MantenimientoEdit.vue'),
    },
];

export default mantenimientoRoutes;