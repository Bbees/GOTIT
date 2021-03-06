
import 'bootstrap-select/dist/css/bootstrap-select.css'
import 'bootstrap-toggle/css/bootstrap-toggle.css'
import 'leaflet/dist/leaflet.css';
import 'leaflet-fullscreen/dist/leaflet.fullscreen.css';
import 'leaflet-easybutton/src/easy-button.css';
import "bootstrap-vue/dist/bootstrap-vue.css";


import "../css/modal.less"
import "../css/leaflet-maps.less"
import "../css/multiselect.less"

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import "bootstrap-select"
$.fn.selectpicker.Constructor.DEFAULTS.style = 'btn-light border';
import "bootstrap-toggle"

import Vue from 'vue'
import { BootstrapVue } from "bootstrap-vue"
import VueI18n from 'vue-i18n'
import filters from './vue-filters'

import i18n from "./i18n"

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.use(BootstrapVue)
Vue.use(VueI18n);
Vue.mixin({
  i18n,
  methods: {
    generateRoute(route, args) {
      return Routing.generate(route, args)
    }
  }
})

Object.entries(filters).forEach(([key, filter]) => {
  Vue.filter(key, filter)
})