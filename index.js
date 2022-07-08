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
      computed: {
        items() {
          let data = o.computed.items.call(this);

          if (this.controls === false) {
            for (const item of data) {
              delete item.flag
              delete item.options
            }
            return data;
          }

          if (this.controls === "flag") {
            for (const item of data) {
              delete item.options
            }
            return data;
          }

          return data;
        },
      },
    });
  }],
});
