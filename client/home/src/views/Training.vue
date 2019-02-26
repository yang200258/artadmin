<template>
  <div class="p-training">
    <top-info />
    <top-bar />
    <sub-nav :navs="subNavs" />
    <div class="training-body">
      <div class="training-main clearfix">
        <div class="main-left fl">
          <div v-if="leftCates && leftCates.name && leftCates.id" class="cate-box">
            <block-head :title="leftCates.name" :hide-btn="true" />
            <div class="cate-dynamic-box">
              <dynamic v-for="dynamic in leftCates.list" :key="dynamic.id" :dynamic="dynamic" />
            </div>
          </div>
          <div style="margin-top:20px;font-size:0;text-align:center">
            <div style="display:inline-block;">
              <pagination :total="page.total_page || 0" :current="1" @change="pn => fetchCates(pn)" />
            </div>
          </div>
        </div>
        <div class="main-right fr">
          <template v-for="(rightCate, rightIdx) in rightCates">
            <!-- 相关的类别list存在数据时才展示，v-if判断 -->
            <div :key="rightCate.id" v-if="rightCate.list && rightCate.list.length" class="cate-box">
              <block-head :title="rightCate.name" :hide-btn="!Boolean(categoryRoute[rightCate.id.toString()])" @blockClick="ele => blockClick(rightCate.id, ele)" />
              <div class="cate-dynamic-box" :class="{clearfix: rightIdx !== 0}">
                <template v-if="rightIdx === 0">
                  <dynamic v-for="dynamic in rightCate.list" :key="dynamic.id" :dynamic="dynamic" mode="2" />
                </template>
                <template v-else>
                  <div v-for="(dynamic, dynamicIdx) in rightCate.list" :key="dynamic.id" class="cate-dynamic-content mode3" :class="{fl: (dynamicIdx % 2) === 0, fr: (dynamicIdx % 2) !== 0}">
                    <dynamic :dynamic="dynamic" :mode="rightIdx === 0 ? '2' : '3'" />
                  </div>
                </template>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
    <bottom-info />
  </div>
</template>

<script>
// @ is an alias to /src
import TopInfo from '../components/TopInfo'
import TopBar from '../components/TopBar'
import BottomInfo from '../components/BottomInfo'
import BlockHead from '../components/BlockHead'
import Dynamic from '../components/Dynamic'
import SubNav from '../components/SubNav'
import Pagination from '../components/Pagination'

export default {
  data () {
    return {
      categoryRoute: {
        1: '/dynamic',
        2: '/training',
        3: '/perform',
        4: '/race',
        5: '/book'
      },
      subNavs: [{ name: 'Home', label: '海南考区', link: '/' }, { name: 'Training', label: '师资培训', link: '/training' }],
      leftCates: {},
      rightCates: [],
      page: {},
      loading: false
    }
  },
  components: { TopInfo, TopBar, BottomInfo, BlockHead, Dynamic, SubNav, Pagination },
  mounted: function () {
    this.fetchCates(1)
  },
  methods: {
    blockClick: function (id, ele) {
      if (ele === 'btn' && this.categoryRoute[id.toString()]) { // 点击了更多按钮 且 有对应的路由
        this.$router.replace({ path: this.categoryRoute[id.toString()] })
      }
    },
    fetchCates: function (pn) {
      console.log('fetchCates', pn)
      if (this.loading) {
        return false
      }
      let rData = {
        pn
      }
      this.loading = true
      this.$ajax('/msg/category2', { data: rData }).then(res => {
        this.loading = false
        if (res && !res.error) {
          this.leftCates = res.data.leftCates
          this.rightCates = res.data.rightCates
          this.page = res.data.page
        }
      }).catch(err => {
        console.log('err', err)
        this.loading = false
      })
    }
  }
}
</script>

<style scoped>
.p-training{
  overflow-x: visible;
}
.training-body{
  overflow-x: visible;
  min-height: 1000px;
  padding-bottom: 20px;
}
.main-left{
  width: 780px;
}
.main-right{
  width: 360px;
}
.cate-box{
  margin-top: 30px;
}
.cate-dynamic-content.mode3{
  width: 47.22%;
}
</style>
