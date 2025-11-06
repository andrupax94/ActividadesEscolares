class ModalesService {
    constructor() {
        this.config = {
            typeModal: 'alert',
            h3: '¿Deseas continuar?',
            info: '',
        };
        this.activo = false;
    }
    abrirModal(config) {
        this.config = config;
        this.activo = true;
        this.render();
    }
    aceptar() {
        var _a, _b;
        this.activo = false;
        this.render();
        (_b = (_a = this.config).onAceptar) === null || _b === void 0 ? void 0 : _b.call(_a);
    }
    cerrar() {
        var _a, _b;
        this.activo = false;
        this.render();
        (_b = (_a = this.config).onCerrar) === null || _b === void 0 ? void 0 : _b.call(_a);
    }
    render() {
        const modal = document.getElementById('modal');
        if (!modal)
            return;
        modal.style.display = this.activo ? 'block' : 'none';
        modal.style.opacity = this.activo ? '1' : '0';
        const pregunta = document.getElementById('modalPregunta');
        if (pregunta) {
            pregunta.hidden = this.config.typeModal !== 'question';
        }
        modal.className = this.config.typeModal;
        modal.querySelector('h3').textContent = this.config.h3;
        modal.querySelector('p').innerHTML = this.config.info;
    }
}
window.ModalesService = new ModalesService();
export default ModalesService;
//# sourceMappingURL=ModalesService.js.map