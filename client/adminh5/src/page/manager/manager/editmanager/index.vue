<template>
    <div class="editmanager-container">
        <div class="edit-header">
            <p>账号信息：</p>
        </div>
        <div class="edit-content">
            <div class="input-container">
                <div class="input-header">
                    <p style="color: red">*</p>
                    <p> 姓名</p>
                </div>
                <el-row><el-col :span="8"><el-input placeholder="请输入姓名" v-model="managerdata.name"></el-input></el-col></el-row>
            </div>
            <div class="input-container">
                <div class="input-header">
                    <p style="color: red">*</p>
                    <p> 登录账号</p>
                </div>
                <el-row><el-col :span="8"><el-input placeholder="请输入登录账号" v-model="managerdata.username"></el-input></el-col></el-row>
            </div>
            <div class="input-container">
                <div class="input-header">
                    <p style="color: red">*</p>
                    <p> 用户身份</p>
                </div>
                <el-row><el-col :span="8"><el-input placeholder="请输入用户身份" v-model="managerdata.identity"></el-input></el-col></el-row>
            </div>
            <div class="checkbox-container">
                <div class="checkbox-container-header">
                    <p style="color: red">*</p>
                    <p>权限分配：</p>
                </div>
                <el-checkbox-group v-model="rightlist" @change="changeRight">
                    <el-checkbox v-for="item in rightlists" :key="item.name" :label="item.name">{{item.label}}</el-checkbox>
                </el-checkbox-group>
            </div>
        </div>
        <div class="edit-footer">
            <el-row type="flex" justify="center" ><el-button @click="saveEdit">保存修改</el-button></el-row>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            rightlist: [],
            rightlists: [{name: 'apply',label: '报名管理'},{name: 'exam',label: '考试管理'},{name: 'msg',label: '消息管理'},{name: 'inform',label: '通知管理',},{name: 'admin',label: '管理员管理'}]
        }
    },
    computed: {
        managerdata(){
            return this.$store.state.manager.managerdata
        },
        organ(){
            return this.$store.state.auth.organ
        },
        // rightlist(){
        //     return this.$store.state.manager.rightlist
        // }
    },
    mounted(){
        if(this.$route.params.rightlist) this.rightlist = this.$route.params.rightlist
    },
    methods: {
        //保存修改信息
        saveEdit: function(){
            const {id,name,identity,username} = this.managerdata
            let [apply,exam,msg,inform,admin] = ['0','0','0','0','0']
            console.log(apply,exam,msg,inform,admin);
            for(var i=0;i<this.rightlist.length;i++) {
                if(this.rightlist[i] == 'apply') apply = 1 
                if(this.rightlist[i] == 'exam') exam = 1
                if(this.rightlist[i] == 'msg')   msg = 1
                if(this.rightlist[i] == 'inform')   inform = 1
                if(this.rightlist[i] == 'admin')   admin = 1
            }
            this.$axios({
                url: '/manager/admin/edit',
                method: 'post',
                data: {id,name,identity,username,apply,exam,msg,inform,admin}
            }).then(res=> {
                if(res && !res.error) {
                    alert(res.msg)
                    this.$router.go(-1)
                } else {
                    alert(res.msg)
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        //更改权限数据
        changeRight(val){
            console.log(val);
        }
    }
}
</script>

<style lang="scss" scoped>
    .editmanager-container{
        padding-left: 20px;
        font-size: 16px;
        .edit-header{
            font-weight: 700;
            margin: 50px 0;
        }
        .edit-content{
            padding-left: 40px;
            .input-container{
        margin: 20px 0;
        .input-header{
            display: flex;
            p{
                text-align: center;
                vertical-align: center;
            }
        }
        .el-input{
            margin-top: 10px;
        }
    }
    .select-container{
        margin: 20px 0;
        .select-header{
            display: flex;
            align-items: center;
            p{
                display: inline-block;
                text-align: center;
                vertical-align: center;
            }
        }
        .el-select{
            margin-top: 10px;
        }
    }
    .checkbox-container{
                display: flex;
                align-items: center;
                margin-top: 40px;
                .checkbox-container-header{
                    display: flex;
                    align-items: center;
                    p{
                        word-break: break-all;
                        white-space: nowrap;
                    }
                }
            }
        }
        .edit-footer{
            margin: 60px 0;
            .el-row{
                margin-bottom: 100px;
            }
        }
    }
</style>


