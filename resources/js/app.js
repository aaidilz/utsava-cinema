import './bootstrap';

// Import Swiper
import Swiper from 'swiper/bundle';

// Import Video.js
import videojs from 'video.js';

// Import Toastify
import Toastify from 'toastify-js';
import "toastify-js/src/toastify.css";

// Import SweetAlert2
import Swal from 'sweetalert2';

// Make available globally for blade templates
window.Swiper = Swiper;
window.videojs = videojs;
window.Toastify = Toastify;
window.Swal = Swal;
