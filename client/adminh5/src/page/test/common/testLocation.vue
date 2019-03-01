<template>
    <el-container class="signinfo">
            <el-header><el-row>考试地点安排</el-row></el-header>
            <el-main>
                <div class="baseinfo">
                    <el-form :model="examSite" ref="addexamsite" :rules="rules">
                        <!-- 考试地点1************************************* -->
                        <el-form-item label="考试地点1" prop="address"><el-input v-model="examSite.address" placeholder="请填写考试地点"></el-input></el-form-item>
                        <!-- 考场1*********************** -->
                        <p>考场1：</p>
                        <div class="line"></div>
                        <!-- 考试时间1********** -->
                        <img src="@/assets/images/add.png" class="addimg" @click="addtime">
                        <el-form-item v-for="(time,index) in examSite.time" :key="time.key" :label="'考试时间' + (index+1) + '：'" :prop="'time.' + index + '.value'">
                            <el-date-picker type="datetime" v-model="time.value" placeholder="请设置考场考试时间"></el-date-picker>
                        </el-form-item>
                        <div class="line2"></div>


                        <!-- 添加、删除考点1的考场********************************************************************** -->
                        <div class="editinfo" v-for="(room,index) in examSite.rooms" :key="room.key">
                            <div class="examsite2">
                                <p>考场{{(index+2)}}：</p>
                                <div class="img">
                                    <img src="@/assets/images/delete.png" @click="deleteroom">
                                    <img src="@/assets/images/add.png"  @click="addroom">
                                </div>
                            </div>
                            <div class="line"></div>
                            <!-- 考试时间1*********** -->
                            <el-form-item  label="考试时间1"><el-date-picker type="datetime" v-model="room.time1" placeholder="请设置考场考试时间"></el-date-picker></el-form-item>
                            <!-- 考试时间2************ -->
                            <div class="examsite_address2_time2" v-if="isEmpty">
                                <div class="img2">
                                    <img src="@/assets/images/delete.png"  @click="deletetime(index)">
                                    <img src="@/assets/images/add.png"  @click="addroomtime(index)" >
                                </div>
                                <el-form-item v-for="(item,i) in room.times" :key="item.key" :label="'考试时间' + (i+2) + '：'">
                                    <el-date-picker type="datetime" v-model="item.value" placeholder="请设置考场考试时间"></el-date-picker>
                                </el-form-item>
                            </div>
                            <div class="line" style="width: 100%;"></div>
                        </div>


                        <!-- 添加考点********************************************************************** -->
                        <div v-for="(site,index) in examSite.sites" :key="site.key">
                            <!-- 考试地点2************************************* -->
                            <div class="examsite_address2_time2">
                                <div class="img2">
                                    <img src="@/assets/images/delete.png" @click="deleteSite" >
                                </div>
                                <el-form-item :label="'考试地点' + (index+2) + '：'"><el-input v-model="site.site" placeholder="请填写考试地点"></el-input></el-form-item>
                            </div>
                            <!-- 考场1*********************** -->
                            <p>考场1：</p>
                            <div class="line"></div>
                            <!-- 考试时间1********** -->
                            <div class="examsite_address2_time2">
                                <div class="img2">
                                    <img src="@/assets/images/add.png"  @click="addaddresstime(index)">
                                </div>
                                <el-form-item v-for="(time,index) in site.times" :key="time.key" :label="'考试时间' + (index+1) + '：'">
                                    <el-date-picker type="datetime" v-model="time.value" placeholder="请设置考场考试时间"></el-date-picker>
                                </el-form-item>
                            </div>
                        </div>
                        <el-button class="addsite" @click="addsite">继续添加考点</el-button>
                        <div class="line" style="width: 100%"></div>
                    </el-form>
                </div>
            </el-main>
        </el-container>
</template>

<script>
import {mapState} from 'vuex'
export default {
    data(){
        return{
            isEmpty: true,
        }
    },
    computed: {
        ...mapState('test',{
            examSite: state=> state.examSite,
            rules: state=> state.rules,
        })
        
    },
    methods: {
        //添加考点1--考场1---考试时间
        addtime(){
            this.examSite.time.push({
                value: '',
                key: Date.now()
            })
        },
        //添加考点1--考场2---考试时间
        addroomtime(index){
            console.log(this.examSite.rooms[index]);
          this.examSite.rooms[index].times.push({
                value: '',
                key: Date.now()
            })  
        },
        //删除考点1--考场2---考试时间
        deletetime(index){
            if( this.examSite.rooms[index].times.length == '1') {
                this.isEmpty = false
            }
             this.examSite.rooms[index].times.pop()
        },
        //删除考点1-----考场
        deleteroom(){
             this.examSite.rooms.pop()
        },
        //添加考点1------考场
        addroom(){
            this.examSite.rooms.push({
                time1: '',
                times: [{value: '',key: Date.now()}],
                key: Date.now()
            })
        },
        //添加考点2--考场1---考试时间
        addaddresstime(index){
            this.examSite.sites[index].times.push({
                value: '',
                key: Date.now()
            })
        },
        //添加考点------------
        addsite() {
            this.examSite.sites.push({
                site: '',
                times: [{value: '',key: Date.now()}],
                key: Date.now()
            })
        },
        //删除考点2-----
        deleteSite(){
            this.examSite.sites.pop()
        },
    }
}
</script>

<style lang="scss" scoped>
.signinfo {
    font-size: 16px;
    margin-top: 40px;
    
    .el-header {
        background-color: #545c64;
        height: 50px!important; 
        .el-row {
            font-size: 18px;
            color: #fff;
            line-height: 50px;
        }
    }
    .el-main {
        .baseinfo {
            font-size: 16px;
            p {
                overflow: hidden;
            }
            .el-input,el-date-picker {
                display: block;
                width: 30%;
            }
            .line {
                margin-top: 4px;
                width: 30%;
                border-top: 1px solid #dcdfe6;
            }
            .addimg {
                position: relative;
                left: 29%;
            }
            .line2 {
                margin-top: 4px;
                width: 50%;
                border-top: 1px solid #dcdfe6; 
            }
            .examsite2 {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 30%;
                margin-top: 30px;
                overflow: hidden;
            }
            .img {
                display: flex;
                img {
                    &:first-child {
                        display: inline-block;
                        margin-right: 12px;
                    }
                }
            }
            .examsite_address2_time2 {
                position: relative;
                width: 30%;
                .img2 {
                    position: absolute;
                    right: 0;
                    // top: 14%;
                    margin-top: 10px;
                    z-index : 999;
                    img {
                        z-index : 999;
                        &:first-child {
                            margin-right: 8px;
                            right: 4%;
                        }
                }
                }
                
                .el-input {
                    width: 100%;
                    display: block;
                }
            }
            .addsite {
                margin-left: 50%;
            }
        }
    }
}
</style>

