import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

// Hacer Swal globalmente disponible (como Alpine)
window.Swal = Swal;

window.Alpine = Alpine;
Alpine.start();
