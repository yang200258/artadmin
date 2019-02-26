<template>
  <transition appear appear-class="before-appear">
    <div :id="id" :style="'z-index:' + new Date().getTime()" class="toast-box" @click="hide">{{text}}</div>
  </transition>
</template>

<script>
export default {
  data () {
    return {
      id: '',
      text: ''
    }
  },
  methods: {
    hide () {
      this.$toast.hide(this.id)
    }
  }
}
</script>

<style scoped>
  .toast-box{
    position: fixed;
    box-sizing:border-box;
    left:50%;
    bottom: 150px;
    transform: translate(-50%, -80px);
    max-width: 400px;
    padding: 24px 40px;
    line-height: 42px;
    font-size: 30px;
    text-align: center;
    background: rgba(0,0,0,0.85);
    border-radius:6px;
    color:#fff;
    transition: opacity 300ms, transform 500ms;
  }
  .toast-box.before-appear{
    opacity: 0;
    transform: translate(-50%, 0);
  }
</style>
