<template>
    <el-container class="signinfo">
            <el-header><el-row>考试地点安排</el-row></el-header>
            <el-main>
                <div class="baseinfo">
                    <el-form :model="examSite" ref="addexamsite">
                        <div v-for="(site,index) in examSite" :key="site.key">
                            <!-- 考试地点1************************************* -->
                            <el-form-item>
                                <p style="color: 14px;"><span style="color: red" v-if="index == 0">*</span >考试地点{{index+1}}：</p>
                                <el-input v-model="site.address" placeholder="请填写考试地点"></el-input>
                            </el-form-item>
                            <!-- 考场1*********************** -->
                            <div class="examsite2">
                                <p style="color: 14px;"><span style="color: red">*</span >考场1：</p>
                                <div class="img" v-if="site.rooms.length == 0">
                                    <img src="@/assets/images/add.png"  @click="addroom(index)">
                                </div>
                            </div>
                            <div class="line"></div>
                            <!-- 考试时间********** -->
                            <div class="examsite_address2_time2">
                                <div class="img2"><img src="@/assets/images/add.png" @click="addtime(index)" v-if="site.time.length == 0"></div>
                                <!-- 考试时间1********** -->
                                <el-form-item>
                                    <p style="color: 14px;"><span style="color: red">*</span >考试时间1：</p>
                                    <el-date-picker type="datetime" v-model="site.time1.value" placeholder="请设置考场考试时间"></el-date-picker>
                                </el-form-item>
                                <!-- 考试时间2********** -->
                                <el-form-item v-for="(time,i) in site.time" :key="time.key" v-if="site.time.length !== 0">
                                    <div class="img2">
                                        <img src="@/assets/images/delete.png" style="margin-right:16px" v-if="site.time.length == (i + 1)" @click="deleteRoomTime(index)">
                                        <img src="@/assets/images/add.png" @click="addtime(index)" v-if="site.time.length == (i + 1)">
                                    </div>
                                    <p style="color: 14px;">考试时间{{i+2}}：</p>
                                    <el-date-picker type="datetime" v-model="time.value" placeholder="请设置考场考试时间"></el-date-picker>
                                </el-form-item>
                            </div>
                            <!-- 添加、删除考点的考场********************************************************************** -->
                            <div class="editinfo" v-for="(room,m) in site.rooms" :key="room.key" v-if="site.rooms.length !== 0">
                                <div class="line2"  v-if="site.rooms.length !== 0"></div>
                                <div class="examsite2">
                                    <p style="color: 14px;">考场{{(m+2)}}：</p>
                                    <div class="img">
                                        <img src="@/assets/images/delete.png" style="margin-right:16px" @click="deleteroom(index)" v-if="site.rooms.length == (m+1)">
                                        <img src="@/assets/images/add.png"  @click="addroom(index)" v-if="site.rooms.length == (m+1)">
                                    </div>
                                </div>
                                <div class="line"></div>
                                <!-- 考试时间*********** -->
                                <div class="examsite_address2_time2">
                                    <div class="img2"><img src="@/assets/images/add.png"  @click="addroomtime(index,m)" v-if="room.times.length == 0"></div>
                                    <!-- 考试时间1********** -->
                                    <el-form-item >
                                        <p style="color: 14px;">考试时间1：</p>
                                        <el-date-picker type="datetime" v-model="room.time1.value" placeholder="请设置考场考试时间"></el-date-picker>
                                    </el-form-item>
                                    <!-- 考试时间2************ -->
                                    <div v-for="(item,n) in room.times" :key="item.key"  v-if="room.times.length !== 0">
                                        <div class="img2">
                                            <img src="@/assets/images/delete.png" style="margin-right:16px"  @click="deletetime(index,m)" v-if="room.times.length == (n+1)">
                                            <img src="@/assets/images/add.png"  @click="addroomtime(index,m)" v-if="room.times.length == (n+1)">
                                        </div>
                                        <el-form-item>
                                            <p style="color: 14px;">考试时间{{n+2}}：</p>
                                            <el-date-picker type="datetime" v-model="item.value" placeholder="请设置考场考试时间"></el-date-picker>
                                        </el-form-item>
                                    </div>
                                </div>
                            </div>
                            <div class="line" style="width:100%" v-if="examSite.length !== (index+1)"></div>
                        </div>
                        <el-row class="btn"> 
                            <el-col :span="4"><el-button @click="deleteSite" v-if="examSite.length !== 1">删除考点</el-button></el-col>
                            <el-col :span="4"><el-button @click="addsite">继续添加考点</el-button></el-col>
                        </el-row>
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

        }
    },
    computed: {
        ...mapState('test',{
            examSite: state=> state.examSite,
            isEdit: state=> state.isEdit,
        })
        
    },
    methods: {
        //添加考点1--考场1---考试时间
        addtime(index){
            this.examSite[index].time.push({
                value: '',
                key: Date.now()
            })
        },
        //删除考点1--考场1--考试时间
        deleteRoomTime: function(index){
            this.examSite[index].time.pop()
        },
        //添加考点1------考场
        addroom(index){
            this.examSite[index].rooms.push({
                time1: {value: ''},
                times: [],
                key: Date.now()
            })
        },
        //删除考点1-----考场
        deleteroom(index){
            if(this.isEdit) {
                this.$confirm('该考场可能已安排考生, 确定删除该考场?', '提示', {
                    confirmButtonText: '确定删除',
                    cancelButtonText: '取消',
                    type: 'warning'
                    }).then(() => {
                        this.examSite[index].rooms.pop()
                    }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });          
                });
            } else {
                this.examSite[index].rooms.pop()
            }
        },
        //添加考点1--考场2---考试时间
        addroomtime(index,m){
          this.examSite[index].rooms[m].times.push({
                value: '',
                key: Date.now()
            })  
        },
        //删除考点1--考场2---考试时间
        deletetime(index,m){
             this.examSite[index].rooms[m].times.pop()
        },
        //添加考点2------考场
        addsiteroom: function(){
            
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
            this.examSite.push({address: "",time1: {value: ''},time: [],rooms: []})
        },
        //删除考点2-----
        deleteSite(){
            if(this.isEdit) {
                this.$confirm('该考点可能已安排考生, 确定删除该考场?', '提示', {
                    confirmButtonText: '确定删除',
                    cancelButtonText: '取消',
                    type: 'warning'
                    }).then(() => {
                        this.examSite.pop()
                    }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });          
                });
            } else {
                this.examSite.pop()
            }
            
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
                .el-input__icon,.el-input__prefix {
                    height: 40px;
                    text-align: center;
                    transition: all .3s;
                }
                
                .el-input__prefix {
                    position: absolute;
                    left: 5px;
                    top: 40px;
                    color: #c0c4cc;

                }
                .el-input__suffix {
                    position: absolute;
                    height: 40px;
                    right: 5px;
                    top: 40px;
                    text-align: center;
                    color: #c0c4cc;
                    transition: all .3s;
                    pointer-events: none;
                }
            }
            .line {
                margin-top: 4px;
                width: 30%;
                border-top: 1px solid #dcdfe6;
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
                margin-top: 10px;
                overflow: hidden;
            }
            .img {
                display: flex;
                img {
                    &:first-child {
                        display: inline-block;
                        // margin-right: 8px;
                    }
                }
            }
            .examsite_address2_time2 {
                position: relative;
                width: 30%;
                .el-input,el-date-picker {
                display: block;
                width: 30%;
                .el-input__icon,.el-input__prefix {
                    height: 40px;
                    text-align: center;
                    transition: all .3s;
                }
                
                .el-input__prefix {
                    position: absolute;
                    left: 5px;
                    top: 40px;
                    color: #c0c4cc;

                }
                .el-input__suffix {
                    position: absolute;
                    height: 40px;
                    right: 5px;
                    top: 40px;
                    text-align: center;
                    color: #c0c4cc;
                    transition: all .3s;
                    pointer-events: none;
                }
            }
                .img2 {
                    position: absolute;
                    right: 0;
                    // top: 14%;
                    margin-top: 15px;
                    z-index : 999;
                    img {
                        z-index : 999;
                        &:first-child {
                            // margin-right: 8px;
                            right: 4%;
                        }
                }
                }
                
                .el-input {
                    width: 100%;
                    display: block;
                }
            }
            .btn {
                display: flex;
                justify-content: center;
            }
        }
        
    }
}
</style>

