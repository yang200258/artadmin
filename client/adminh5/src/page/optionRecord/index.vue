<template>
    <div class="option-container">
        <table-data :isPagination="'true'" :totalNumber="total" :pageSize="limit" :currentPage="pn" :isEdit="'true'" :editName="'刷新'" :head="head" 
        :tableData="optionData" :loadingTable="loadingOptionRecord" @editInfo="refresh" @handleCurrentChange="handleCurrentChange"></table-data>
    </div>
</template>

<script>
export default {
    data(){
        return{
            total: 200,
            limit: 50,
            pn: 1,
            head: [{key:'id',name: '管理员ID'},{key:'name',name: '姓名'},{key:'optionType',name: '操作类型'},
                    {key:'optionContent',name: '操作内容'},{key:'optionTime',name: '操作时间'}],
            optionData: [],
            loadingOptionRecord: false
        }
    },
    mounted(){
        this.getOptionRecord()
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
                url: '/optionRecord',
                method: 'post',
                data: {pn}
            }).then(res=>{
                console.log('获取到的操作数据',res)
                if(res && !res.error) {
                    this.optionData = res.data.list
                    this.$set(this,'',res.data.page)
                } else {
                    this.$message.warn(res.msg)
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

