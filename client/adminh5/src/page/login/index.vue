<template>
    <div class="sys-login">
        <div class="login-area">
            <div class="form-group">
                <el-form :model="loginForm" :rules="loginRules" ref="loginForm" label-position="left" label-width="0px">
                    <el-form-item prop="name">
                        <el-input v-model="loginForm.name" type="text" placeholder="请输入用户名"></el-input>
                    </el-form-item>
                    <el-form-item prop="password">
                        <el-input v-model="loginForm.password" v-on:keyup.enter.native="submitForm()" type="password" placeholder="请输入密码"></el-input>
                    </el-form-item>
                    <a class="btn-login" type="primary" @click="submitForm()">{{$t('global.login')}}</a>
                </el-form>
                <div class="err-msg">{{sysMsg}}</div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import setTheme from "@/util/setTheme"

export default {
    data() {
        return {
            loginForm: {
                name: '',
                password: '',
                captcha: ''
            },
            loginRules: {
                name: 
                [
                    {required: true, message: '', trigger: 'blur'},
                    {min: 2,max: 8,message: '长度在 2 到 8 个字符'}
                ],
                password :[
                    {required: true, message: '', trigger: 'blur'},
                    {min: 6,max: 16,message: '长度在 6 到 16 个字符'},
                    { pattern: /^(\w){6,20}$/, message: '只能输入6-20个字母、数字、下划线' }
                ],
            },
            sysMsg: ''
        }
    },
    computed: {
        ...mapState({
            lang: state => state.lang,
            theme: state => state.theme
        })
    },
    watch: {
        // "captcha.show"(val){
        //     this.loginRules.captcha[0].required = val
        // }
    },
    beforeMount(){
        // 初始化错误信息。保证单独点击input时可以弹出正确的错误提示
        this.setErrMsg()
    },
    methods: {
        ...mapActions({
            login: 'auth/login',
            getOption: 'auth/getOption',
            getOrgan: 'auth/getOrgan'
            
        }),
        submitForm(){
            this.$refs.loginForm.validate((valid) => {
                console.log('valid',valid);
                if (valid) {
                    this.login({
                        username: this.loginForm.name,
                        password: this.loginForm.password
                    }).then(res => {
                        if(res && !res.err){
                            this.$router.push('signup')
                            this.getOption().then(res=> {
                                console.log('成功获取操作信息',res);
                                this.getOrgan().then(res=> {
                                    console.log('成功获取机构信息',res);
                                }).catch(err=> {
                                    console.log(err);
                                })
                            }).catch(err=> {
                                console.log(err);
                            })
                            
                        }
                    }).catch(err=> {
                        console.log('登录失败相应',err);
                        this.sysMsg = err.msg
                    })
                } else {
                    alert('请输入合法的用户名/密码')
                    return false
                }
            });
        },
        changeTheme(val){
            if(val == this.lang) return
            setTheme(val)
            this.$store.commit("setThemeColor", val)
        },
        setErrMsg(){
            this.loginRules.name[0].message = this.$t('global.errMsg.inputRequired', {cont: this.$t('global.username')})
            this.loginRules.password[0].message = this.$t('global.errMsg.inputRequired', {cont: this.$t('global.password')})
            // this.loginRules.captcha[0].message = this.$t('global.errMsg.inputRequired', {cont: this.$t('global.captcha')})
        }
    }
}
</script>

<style lang="scss">
@import '../../assets/css/theme-dark.scss';
@import '../../assets/css/theme-default.scss';
@import '../../assets/css/theme/dark.scss';
@import '../../assets/css/theme/default.scss';
  @import '../../assets/css/page/login.scss';
</style>



