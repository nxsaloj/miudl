<template></template>
<script type="text/babel">
import * as jquery from 'jquery'
import * as toast from 'jquery-toast-plugin'

export default {
  name: "toastel", // this is what the Warning is talking about.
  model: {
    event: "show"
  },
  props: {
    mensaje: String,
    titulo:String
  },
  methods: {
    showMessage: function(message) {
      if(message)
      {
        this.$emit("show");
        $.toast({
            heading: (this.$props.titulo)? this.$props.titulo:"Información",
            text: message,
            position: String("top-right"),
            stack: false,
            loader: false,
            icon:"success",
            showHideTransition: 'slide',
            bgColor: '#33AA66',
        });
      }
    }
  },
  mounted() {
    this.showMessage(this.$props.mensaje);
  }
};
</script>