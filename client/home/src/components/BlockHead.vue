<template>
  <div class="c-block-head clearfix" :style="{height: height + 'px', lineHeight: height + 'px', borderBottom: '1px solid ' + borderColor}">
    <div @click.stop="blockClick('title')" class="title fl">{{title}}</div>
    <div v-if="!hideBtn" @click.stop="blockClick('btn')" class="more-btn cursor-pointer fr">{{btnText}}</div>
  </div>
</template>

<script>
export default {
  props: {
    height: {
      type: Number,
      default: 40
    },
    fontSize: {
      type: Number,
      default: 20
    },
    title: {
      type: String,
      required: true
    },
    btnText: {
      type: String,
      default: '更多>>'
    },
    borderColor: {
      type: String,
      default: '#EFDFC9'
    },
    hideBtn: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {}
  },
  methods: {
    blockClick: function (ele) {
      this.$emit('blockClick', ele)
    }
  }
}
</script>

<style scoped>
.c-block-head{
  color: #76593D;
}
.title{
  font-size: 20px;
}
.more-btn{
  font-size: 16px;
}
.more-btn:hover{
  color: #F5A623;
  text-decoration: underline;
}
</style>
