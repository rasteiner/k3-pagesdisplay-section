panel.plugin("rasteiner/k3-pagesdisplay-section", {
  use: [(Vue) => {
    const o = Vue.component("k-pages-section").options;
    Vue.component("k-pagesdisplay-section", {
      extends: o,
      props: {
        controls: {
          type: [Boolean, String],
          default: true,
        },
      },
      methods: {
        items(data) {
          if(this.controls === false) return data;

          data = o.methods.items.call(this, data);

          if(this.controls === "flag") {
            for(const item of data) {
              delete item.flag.click
              delete item.options
            }
          }

          return data;
        },
      },
    });
  }],
});
