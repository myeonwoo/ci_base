// Jquery datetime-picker 기능 추가
// 예제: <datetime-picker name="display_end_dt" class="btn date_picker" date-format="yy-mm-dd H:i" v-bind:value="occasion.display_end_dt"></datetime-picker>
Vue.component('datetime-picker', {
  template: '<input/>',
  props: [ 'format' ],
  mounted: function() {
    $(this.$el).datetimepicker({
      format: this.format
    });
  },
  beforeDestroy: function() {
    $(this.$el).datetimepicker('hide').datetimepicker('destroy');
  }
});