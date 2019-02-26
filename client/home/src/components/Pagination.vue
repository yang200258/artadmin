<template>
  <div class="c-pagination clearfix">
    <div v-show="!disabled.first" class="button first-btn fl" :class="{disabled: disabled.first}" @click="pageClick(1, disabled.first)">首页</div>
    <div v-show="!disabled.pre" class="button pre-btn fl" :class="{disabled: disabled.pre}" @click="pageClick(currentPage - 1, disabled.pre)">上一页</div>
    <div class="button pre-section fl" v-show="total > 5 && currentPage > 3" @click.stop="pageClick(currentPage - 5)">...</div>
    <div class="button page fl" :class="{disabled: disabled.disabledPage === page, current: currentPage === page}" v-for="page in pages" :key="page" @click.stop="pageClick(page, disabled.disabledPage === page)">{{page}}</div>
    <div class="button next-section fl" v-show="total > 5 && (currentPage < total - 2)" @click.stop="pageClick(currentPage + 5)">...</div>
    <div v-show="!disabled.next" class="button next-btn fl" :class="{disabled: disabled.next}" @click="pageClick(currentPage + 1, disabled.next)">下一页</div>
    <div v-show="!disabled.last" class="button last-btn fl" :class="{disabled: disabled.last}" @click="pageClick(total, disabled.last)">末页</div>
  </div>
</template>

<script>
export default {
  props: {
    total: {
      type: Number,
      required: true
    },
    current: {
      type: Number,
      default: 0
    },
    pageCount: {
      type: Number,
      default: 5
    }
  },
  data () {
    return {
      cur: 0
    }
  },
  computed: {
    currentPage: function () {
      let current = this.cur || this.current
      return current || 0
    },
    pages: function () {
      let current = this.currentPage || this.current || 0
      let pages = this.getPages(current)
      return pages
    },
    disabled: function () {
      let currentPage = this.currentPage
      let pages = this.pages
      let total = this.total
      let disabled = {}
      disabled.first = !(pages && pages.length > 1 && currentPage > 1)
      disabled.last = !(pages && pages.length > 1 && currentPage < total)
      disabled.pre = !(pages && pages.length > 1 && currentPage > 1)
      disabled.next = !(pages && pages.length > 1 && currentPage < total)
      disabled.disabledPage = currentPage
      return disabled
    }
  },
  methods: {
    changeCurrentPage: function (num) {
      console.log('changeCurrentPage')
      let currentPage = 0
      if (num > this.total) {
        currentPage = this.total
      } else if (num < 1) {
        currentPage = 1
      } else {
        currentPage = num
      }
      this.cur = currentPage
      return currentPage
    },
    getPages: function (num) {
      let pages = []
      if (parseInt(this.total) < 1) {
        pages = []
      } else if (parseInt(this.total) >= 1 && parseInt(this.total) <= this.pageCount) {
        for (let i = 0; i < this.total; i++) {
          pages.push(i + 1)
        }
      } else if (parseInt(this.total) > this.pageCount) {
        let half = Math.floor(this.pageCount / 2)
        for (let i = 0; i < this.pageCount; i++) {
          pages.push(num - half + i)
        }
        pages = pages.filter(item => item > 0 && item <= this.total)
        let addLen = 5 - pages.length
        for (let i = 0; i < addLen; i++) {
          if (num <= half) {
            pages.push(pages[pages.length - 1] + 1)
          } else if (num >= pages.length - half) {
            pages.unshift(pages[0] - 1)
          }
        }
      }
      return pages
    },
    pageClick: function (page, disabled) {
      console.log('pageClick', page, disabled)
      if (disabled) {
        return false
      }
      this.changeCurrentPage(page)
      this.$emit('change', page)
    }
  }
}
</script>

<style scoped>
.button{
  font-size: 16px;
  color: #76593D;
  line-height: 38px;
  min-width: 48px;
  border: 1px solid #76593D;
  padding: 0 14px;
  box-sizing: border-box;
  background: #FBF1E6;
  text-align: center;
  cursor: pointer;
  margin-left: 10px;
  transition: all 0.3s;
}
.button:hover{
  color: #816101;
  background: #ddb990;
  box-shadow: 0 0 5px 0 rgba(0,0,0,0.1) inset;
}
.button:first-child{
  margin-left: 0;
}
.button.disabled{
  background: #ccc;
  font-weight: normal;
  box-shadow: none;
}
.button.current{
  background: #A6835C;
  color: #fff;
}
</style>
