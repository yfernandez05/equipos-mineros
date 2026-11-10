const tipoEquipoRoutes = [
    {
        path: 'tipos-equipos',
        name: 'spa.tiposEquipo',
        component: () => import('../../views/tiposEquipos/TipoEquipoIndex.vue'),
    },
    {
        path: 'tipos-equipos/crear',
        name: 'spa.tipoEquipo.register',
        component: () => import('../../views/tiposEquipos/TipoEquipoCreate.vue'),
    },
    {
        path: '/tipos-equipos/editar/:id',
        name: 'spa.tipoEquipo.edit',
        component: () => import('../../views/tiposEquipos/TipoEquipoEdit.vue'),
    },
];
export default tipoEquipoRoutes;
