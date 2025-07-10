import './bootstrap';
import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import Glider from 'glider-js'
import L from 'leaflet';
import intersect from '@alpinejs/intersect'
Alpine.plugin(intersect)

import flatpickr from "flatpickr"
window.flatpickr = flatpickr;

import Cropper from 'cropperjs';
window.Cropper = Cropper;


import TomSelect from "tom-select";
window.TomSelect = TomSelect



document.addEventListener('alpine:init', () => {

    Alpine.data('gliderGallery', () => ({
        init() {
            new Glider(this.$refs.carousel, {
                slidesToShow: 1,
                dots: this.$refs.dots,
                arrows: {
                    prev: this.$refs.prev,
                    next: this.$refs.next
                },
                draggable: true,
                rewind: true,
                scrollLock: true,
            });
        },
    }));
})
