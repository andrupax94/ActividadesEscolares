import ModalesService from './ModalesService';

// Exponer el servicio globalmente para que Blade pueda llamarlo
(window as any).ModalesService = new ModalesService();
export default ModalesService;
console.log('Main.ts cargado correctamente');

