<template>
    <div class="option-container">
        <table-data :isPagination="'true'" :totalNumber="total" :pageSize="limit" :currentPage="pn" :isEdit="'true'" :editName="'刷新'" :head="head" 
        :tableData="optionData" :loadingTable="loadingOptionRecord" @editOption="refresh" @handleCurrentChange="handleCurrentChange"></table-data>
    </div>
</template>

<script>
import tableData from '@/components/common/tableData'
export default {
    data(){
        return{
            total: 0,
            limit: 50,
            pn: 1,
            head: [{key:'admin_id',name: '管理员ID'},{key:'admin_name',name: '姓名'},{key:'optiontype',name: '操作类型'},
                    {key:'content',name: '操作内容'},{key:'create_at',name: '操作时间'}],
            optionData: [],
            loadingOptionRecord: false,
            optionType: ['报名管理','考试管理','通知管理','信息管理','管理员管理']
        }
    },
    mounted(){
        this.getOptionRecord()
    },
    components: {
        tableData
    },
    methods: {
        refresh: function(){
            this.getOptionRecord()
        },
        handleCurrentChange: function(val){
            this.getOptionRecord(val)
        },
        getOptionRecord: function(pn){
            this.loadingOptionRecord = true
            pn = pn || 1
            this.$axios({
                url: '/record/list',
                method: 'post',
                data: {pn}
            }).then(res=>{
                console.log('获取到的操作数据',res)
                if(res && !res.error) {
                    const list = res.data.list
                    const page = res.data.page
                    list.forEach(item=> {
                        item.optiontype = this.optionType[item.type-1]
                    })
                    this.optionData = list
                    this.total = page.total
                    this.limit = page.limit
                    this.pn = page.pn
                } else {
                    this.$message.warning(res.msg)
                }
                this.loadingOptionRecord = false
            }).catch(err=>{
                this.loadingOptionRecord = false
                console.log(err);
            })
        }
    }
}
</script>

