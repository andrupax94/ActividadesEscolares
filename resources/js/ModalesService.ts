type ModalType = 'alert' | 'question';

interface ModalConfig {
    typeModal: ModalType;
    h3: string;
    info: string;
    onAceptar?: () => void;
    onCerrar?: () => void;
}

class ModalesService {
    private config: ModalConfig = {
        typeModal: 'alert',
        h3: '¿Deseas continuar?',
        info: '',
    };

    private activo: boolean = false;

    abrirModal(config: ModalConfig) {
        this.config = config;
        this.activo = true;
        this.render();
    }

    aceptar() {
        this.activo = false;
        this.render();
        this.config.onAceptar?.();
    }
    confirmar(mensaje: string, form: HTMLFormElement): boolean {
        console.log('hola');

        this.abrirModal({
            typeModal: 'question',
            h3: 'Confirmación',
            info: mensaje,
            onAceptar: () => form.submit(),
            onCerrar: () => { }
        });

        return false; // Detiene el envío del formulario
    }

    cerrar() {
        this.activo = false;
        this.render();
        this.config.onCerrar?.();
    }

    private render() {
        console.log('render');

        const modal = document.getElementById('modal');
        if (!modal) return;
        console.log('render2');

        modal.style.display = this.activo ? 'block' : 'none';
        modal.style.opacity = this.activo ? '1' : '0';

        const pregunta = document.getElementById('modalPregunta');
        if (pregunta) {
            pregunta.hidden = this.config.typeModal !== 'question';
        }

        modal.className = this.config.typeModal;
        modal.querySelector('h3')!.textContent = this.config.h3;
        modal.querySelector('p')!.innerHTML = this.config.info;
    }
}

(window as any).ModalesService = new ModalesService();
export default ModalesService;
