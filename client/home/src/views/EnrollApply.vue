<template>
  <div class="p-enroll-apply">
    <div class="enroll-apply-body">
      <div class="form-box avatar-box clearfix">
        <div class="avatar-title required-title fl clearfix"><i class="ykfont yk-required fl"></i><span class="fl">考生照片：</span></div>
        <div class="avatar-image fl" :style="{backgroundImage: 'url(' + (form.avatar.url ? form.avatar.url : defaultAvatar) + ')'}">
          <div v-show="form.avatar.uploading || form.avatar.uploadError" class="image-tip" style="line-height:227px">{{form.avatar.uploadTip}}</div>
        </div>
        <div class="avatar-state fl">
          <div class="state-text">
            <i class="ykfont yk-warning fl"></i>
            <div class="warining-text fl">上传头像注意事项：<br />1.上传二寸的电子证件照片，建议626*413，大小不超过800K；<br />2.照片的有效格式为PNG、JPG、BMP中的一种。</div>
            <el-upload
              style="clear:both;position:relative;left:24px;top:56px"
              :disabled="form.avatar.uploading"
              :multiple="false"
              action=" "
              :data="form.avatar.upload_data"
              :drag="false"
              :show-file-list="false"
              :before-upload="beforeAvatarUpload"
              :http-request="e => httpRequest('avatar', e)">
              <div class="avatar-btn cursor-pointer">上传照片</div>
            </el-upload>
          </div>
        </div>
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">姓名：</span></div>
        <input class="full-input" v-model="form.name.value" placeholder="请填写真实姓名" @input="e => inputEnter('name', e)" />
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">拼音或英文：</span></div>
        <div class="clearfix">
          <input class="full-input fl" :disabled="!editPinyin" v-model="form.pinyin.value" placeholder="拼音将自动填写" />
          <div class="pinyin-btn cursor-pointer fl" @click.stop="changeEditPinyin">{{editPinyin ? '确定' : '校正'}}</div>
        </div>
      </div>
      <div class="form-box clearfix">
        <div class="required-title fl clearfix"><i class="ykfont yk-required fl"></i><span class="fl">性别：</span></div>
        <label class="gender-label cursor-pointer fl clearfix">
          <input type="radio" class="gender-radio cursor-pointer fl" value="1" v-model="form.gender.value" @change="genderChange" />
          <span class="fl">{{genderText['1']}}</span>
        </label>
        <label class="gender-label cursor-pointer fl clearfix">
          <input type="radio" class="gender-radio cursor-pointer fl" value="2" v-model="form.gender.value" @change="genderChange" />
          <span class="fl">{{genderText['2']}}</span>
        </label>
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">出生日期：</span></div>
        <el-date-picker
          v-model="form.birthday.value"
          type="date"
          value-format="yyyy-MM-dd"
          placeholder="年-月-日"
          :clearable="false"
          prefix-icon="none"
          class="select-picker"
          @change="value => pickerChange('birthday', value)">
        </el-date-picker>
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">国籍：</span></div>
        <el-select v-model="form.nationality.value" placeholder="请选择国籍" class="select-picker" @change="value => pickerChange('nationality', value)">
          <el-option
            v-for="item in nationalitys"
            :key="item"
            :label="item"
            :value="item">
          </el-option>
        </el-select>
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">民族：</span></div>
        <el-select v-model="form.volk.value" placeholder="请选择民族" class="select-picker" @change="value => pickerChange('volk', value)">
          <el-option
            v-for="item in volks"
            :key="item"
            :label="item"
            :value="item">
          </el-option>
        </el-select>
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">证件类型：</span></div>
        <el-select v-model="form.cardtype.value" placeholder="请选择证件类型" class="select-picker" @change="value => pickerChange('cardtype', value)">
          <el-option
            v-for="item in cardtypes"
            :key="item"
            :label="item"
            :value="item">
          </el-option>
        </el-select>
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">证件号码：</span></div>
        <input class="full-input" v-model="form.cardnumber.value" @input="cardnumberInput" placeholder="请输入证件号码" />
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">联系电话：</span></div>
        <input class="full-input" v-model="form.phone.value" @input="phoneInput" placeholder="请输入联系电话" />
      </div>
      <div class="line-h"></div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">报考专业：</span></div>
        <el-select v-model="form.major.value" placeholder="请选择报考专业" class="select-picker" @change="value => pickerChange('major', value)">
          <el-option
            v-for="item in majors"
            :key="item.value"
            :label="item.text"
            :value="item.value">
          </el-option>
        </el-select>
        <!-- <div class="state-text clearfix" style="margin-top:10px;">
          <i class="ykfont yk-warning fl"></i>
          <div class="warining-text fl">报考基础乐科的考生，考试曲目1、2都填写"无"</div>
        </div> -->
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">报考级别：</span></div>
        <el-select v-model="form.level.value" placeholder="请选择报考级别" class="select-picker" @change="value => pickerChange('level', value)">
          <el-option
            v-for="item in levels"
            :key="item"
            :label="item"
            :value="item">
          </el-option>
        </el-select>
      </div>
      <div v-show="continuitys && continuitys[0]" class="form-box clearfix" style="width:458px">
        <div class="required-title fl clearfix" style="line-height:40px;padding-bottom:0"><i class="ykfont yk-required fl"></i><span class="fl">是否连考：</span></div>
        <el-select v-model="form.continuity.value" placeholder="请选择是否连考" class="short-select-picker fr" @change="value => pickerChange('continuity', value)">
          <el-option
            v-for="item in continuitys"
            :key="item.value"
            :label="item.text"
            :value="item.value">
          </el-option>
        </el-select>
      </div>
      <div v-show="form.lastgetcertificate.required" class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">最近一次获得同专业考级证书是：</span></div>
        <div class="clearfix">
          <el-select v-model="form.lastgetcertificate.year.value" placeholder="年" class="short-select-picker2 fl" @change="value => pickerChange('lastgetyear', value)">
            <el-option
              v-for="item in years"
              :key="item.value"
              :label="item.text"
              :value="item.value">
            </el-option>
          </el-select>
          <el-select v-model="form.lastgetcertificate.month.value" placeholder="月" class="short-select-picker2 fl" @change="value => pickerChange('lastgetmonth', value)">
            <el-option
              v-for="item in months"
              :key="item.value"
              :label="item.text"
              :value="item.value">
            </el-option>
          </el-select>
          <el-select v-model="form.major.value" placeholder="专业" :disabled="true" class="short-select-picker2 fl disabled"></el-select>
          <el-select v-model="levels[levels.indexOf(form.level.value) - 1]" placeholder="级别" :disabled="true" class="short-select-picker2 fl disabled"></el-select>
        </div>
      </div>
      <div v-show="form.majorcertificate.required" class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">上传专业证书：</span></div>
        <div class="clearfix">
          <div class="certificate-image-box fl" :style="{backgroundImage: form.majorcertificate.url ? ('url(' + form.majorcertificate.url + ')') : 'none'}">
            <div v-show="form.majorcertificate.uploading || form.majorcertificate.uploadError" class="image-tip" style="line-height:227px">{{form.majorcertificate.uploadTip}}</div>
          </div>
          <el-upload
            class="fl"
            style="margin-top:200px;margin-left:70px"
            :disabled="form.majorcertificate.uploading"
            :multiple="false"
            action=" "
            :data="form.majorcertificate.upload_data"
            :drag="false"
            :show-file-list="false"
            :http-request="e => httpRequest('majorcertificate', e)">
            <div class="pinyin-btn cursor-pointer fl" style="margin-left:0">上传证书</div>
          </el-upload>
        </div>
        <div class="state-text clearfix" style="margin-top:10px;">
          <i class="ykfont yk-warning fl"></i>
          <div class="warining-text fl">报考演唱演奏10级，需上传本考级机构同专业9级证书；报考表演文凭级，需上传本考级机构同专业10级证书</div>
        </div>
      </div>
      <div v-show="form.basicmusiccertificate.required" class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">上传基本乐科证书：</span></div>
        <div class="clearfix">
          <div class="certificate-image-box fl" :style="{backgroundImage: form.basicmusiccertificate.url ? ('url(' + form.basicmusiccertificate.url + ')') : 'none'}">
            <div v-show="form.basicmusiccertificate.uploading || form.basicmusiccertificate.uploadError" class="image-tip" style="line-height:227px">{{form.basicmusiccertificate.uploadTip}}</div>
          </div>
          <el-upload
            class="fl"
            style="margin-top:200px;margin-left:70px"
            :disabled="form.basicmusiccertificate.uploading"
            :multiple="false"
            action=" "
            :data="form.basicmusiccertificate.upload_data"
            :drag="false"
            :show-file-list="false"
            :http-request="e => httpRequest('basicmusiccertificate', e)">
            <div class="pinyin-btn cursor-pointer fl" style="margin-left:0">上传证书</div>
          </el-upload>
        </div>
        <div class="state-text clearfix" style="margin-top:10px;">
          <i class="ykfont yk-warning fl"></i>
          <div class="warining-text fl">报考演唱演奏3~5级必须上传一级基本乐科证书，6~9级必须上传 二级基本乐科证书，10级或以上必须上传三级基本乐科证书；报考基本乐科2~6级，需上传上一级别证书，选择连考的考生上传最近一次获得的乐科证书即可</div>
        </div>
      </div>
      <div v-show="form.major.value !== '基本乐科' && form.major.value" class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">考试曲目1：</span></div>
        <input class="full-input" v-model="form.bent1.value" placeholder="请输入考试曲目" @change="e => inputChange('bent1', e)" />
      </div>
      <div v-show="form.major.value !== '基本乐科' && form.major.value" class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">考试曲目2：</span></div>
        <input class="full-input" v-model="form.bent2.value" placeholder="请输入考试曲目" @change="e => inputChange('bent2', e)" />
      </div>
      <div v-show="form.major.value !== '基本乐科' && form.major.value" class="form-box">
        <div class="required-title"><span>考试曲目3：</span></div>
        <input class="full-input" v-model="form.bent3.value" placeholder="请输入考试曲目" @change="e => inputChange('bent3', e)" />
      </div>
      <div v-show="form.major.value !== '基本乐科' && form.major.value" class="form-box">
        <div class="required-title"><span>考试曲目4：</span></div>
        <input class="full-input" v-model="form.bent4.value" placeholder="请输入考试曲目" @change="e => inputChange('bent4', e)" />
      </div>
      <div v-show="form.major.value !== '基本乐科' && form.major.value" class="form-box">
        <div class="required-title"><span>考试曲目5：</span></div>
        <input class="full-input" v-model="form.bent5.value" placeholder="请输入考试曲目" @change="e => inputChange('bent5', e)" />
      </div>
      <div class="line-h"></div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">填表人：</span></div>
        <input class="full-input" v-model="form.fillter.value" placeholder="填表人姓名" @change="e => inputChange('fillter', e)" />
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">指导老师：</span></div>
        <input class="full-input" v-model="form.teacher.value" placeholder="指导老师姓名" @change="e => inputChange('teacher', e)" />
      </div>
      <div class="form-box">
        <div class="required-title clearfix"><i class="ykfont yk-required fl"></i><span class="fl">老师电话：</span></div>
        <input class="full-input" v-model="form.teacherphone.value" @input="teacherPhoneInput" placeholder="指导老师电话" @change="e => inputChange('teacherphone', e)" />
      </div>
    </div>
    <div class="bottom-buttons">
      <div class="state-text clearfix">
        <i class="ykfont yk-warning fl"></i>
        <div class="warining-text fl">报名信息一旦提交，不可再进行修改</div>
      </div>
      <div class="buttons-box clearfix">
        <div class="save-button cursor-pointer fl" @click.stop="saveForm">保存</div>
        <div class="submit-button cursor-pointer fr" @click.stop="submitTap">提交</div>
      </div>
    </div>
  </div>
</template>

<script>
import Mtils from 'mtils'
import axios from 'axios'
import { mapState } from 'vuex'
import globalConstant from '../lib/globalConstant'
const defaultAvatar = require('../assets/image/default_avatar.png')

let years = []
let months = []
const nowYear = new Date().getFullYear()
for (let i = 0; i < 100; i++) {
  years.push({
    value: (nowYear - i).toString(),
    text: (nowYear - i).toString() + '年'
  })
}
for (let j = 0; j < 12; j++) {
  months.push({
    value: (j + 1).toString(),
    text: (j + 1).toString() + '月'
  })
}
const initialForm = {
  avatar: {
    value: '',
    required: true,
    valid: false,
    url: '',
    id: '',
    tip: '请上传照片',
    upload_data: {},
    uploading: false,
    uploadError: false,
    uploadTip: ''
  },
  name: {
    required: true,
    value: '',
    valid: false,
    tip: '请填写姓名'
  },
  pinyin: {
    required: true,
    value: '',
    valid: false,
    tip: '请填写拼音或英文'
  },
  gender: {
    required: true,
    value: '',
    valid: false,
    tip: '请选择性别'
  },
  birthday: {
    required: true,
    value: '',
    text: '',
    valid: false,
    tip: '请选择出生日期'
  },
  nationality: { // 国籍
    required: true,
    value: '中国',
    text: '中国',
    valid: true,
    tip: '请选择国籍'
  },
  volk: { // 民族
    required: true,
    value: '汉族',
    text: '汉族',
    valid: true,
    tip: '请选择民族'
  },
  cardtype: { // 证件类型
    required: true,
    value: '身份证',
    text: '身份证',
    valid: true,
    tip: '请选择证件类型'
  },
  cardnumber: { // 证件号码
    required: true,
    value: '',
    valid: false,
    tip: '请填写证件号码'
  },
  phone: {
    required: true,
    value: '',
    valid: false,
    tip: '请填写联系电话'
  },
  major: {
    required: true,
    value: '',
    text: '',
    valid: false,
    tip: '请选择报考专业'
  },
  level: {
    required: true,
    value: '',
    text: '',
    valid: false,
    tip: '请选择报考级别'
  },
  continuity: { // 是否连考
    required: false,
    value: '',
    text: '',
    valid: false,
    tip: '请选择是否连考'
  },
  lastgetcertificate: {
    required: false,
    year: {
      required: false,
      value: '',
      text: '',
      valid: false,
      tip: '请选择年份'
    },
    month: {
      required: false,
      value: '',
      text: '',
      valid: false,
      tip: '请选择月份'
    }
  },
  majorcertificate: { // 专业证书
    upload_data: {},
    value: '',
    required: false,
    valid: false,
    url: '',
    id: '',
    tip: '请上传专业证书',
    uploading: false,
    uploadError: false,
    uploadTip: ''
  },
  basicmusiccertificate: { // 基本乐科证书
    upload_data: {},
    value: '',
    required: false,
    valid: false,
    url: '',
    id: '请上传基本乐科证书',
    tip: '',
    uploading: false,
    uploadError: false,
    uploadTip: ''
  },
  bent1: { // 曲目1
    required: false,
    value: '',
    valid: false,
    tip: '请填写考试曲目1'
  },
  bent2: {
    required: false,
    value: '',
    valid: false,
    tip: '请填写考试曲目2'
  },
  bent3: {
    required: false,
    value: '',
    valid: false,
    tip: '请填写考试曲目3'
  },
  bent4: {
    required: false,
    value: '',
    valid: false,
    tip: '请填写考试曲目4'
  },
  bent5: {
    required: false,
    value: '',
    valid: false,
    tip: '请填写考试曲目5'
  },
  fillter: { // 填表人
    required: true,
    value: '',
    valid: false,
    tip: '请填写填表人姓名'
  },
  teacher: { // 指导老师
    required: true,
    value: '',
    valid: false,
    tip: '请填写老师姓名'
  },
  teacherphone: { // 老师电话
    required: true,
    value: '',
    valid: false,
    tip: '请填写老师电话'
  }
}
export default {
  name: 'EnrollApply',
  data () {
    return {
      exam_id: '',
      submitting: false,
      roles: globalConstant.roles,
      defaultAvatar: defaultAvatar,
      genderText: {
        1: '男',
        2: '女'
      },
      nationalitys: [],
      volks: [],
      cardtypes: [],
      majors: [],
      levels: [],
      continuitys: [],
      years: years,
      months: months,
      editPinyin: false,
      form: initialForm,
      saveTimer: null
    }
  },
  computed: {
    ...mapState('user', ['storeUD'])
  },
  mounted () {
    console.log(this.$route.query.id)
    this.exam_id = this.$route.query.id
    this.getOptions()
    this.getForm()
  },
  beforeDestroy () {
    if (this.saveTimer) {
      clearTimeout(this.saveTimer)
    }
  },
  methods: {
    getOptions: function () {
      this.$ajax('/option').then(res => {
        if (!res.error && res.data) { // 获取信息成功
          let { nationality, nation, major, certificate } = res.data
          let majors = []
          for (let mj in major) {
            majors.push({ value: mj, text: mj, levels: major[mj] })
          }
          let formNationality = {
            required: true,
            value: nationality[0],
            text: nationality[0],
            valid: true,
            tip: '请选择国籍'
          }
          let formVolk = {
            required: true,
            value: nation[0],
            text: nation[0],
            valid: true,
            tip: '请选择民族'
          }
          let formCardtype = { // 证件类型
            required: true,
            value: certificate[0],
            text: certificate[0],
            valid: true,
            tip: '请选择证件类型'
          }
          this.nationalitys = nationality
          this.volks = nation
          this.majors = majors
          this.cardtypes = certificate
          this.majors = majors
          this.form.nationality = formNationality
          this.form.volk = formVolk
          this.form.cardtype = formCardtype
        }
      })
    },
    changeEditPinyin: function () {
      this.editPinyin = !this.editPinyin
    },
    uploadImage: function (type) {
      console.log('uploadImage', type)
    },
    beforeAvatarUpload (file) {
      const isLt10M = file.size / 1024 / 1024 <= 0.8 // 限制上传图片小雨10M
      if (!isLt10M) {
        this.$toast('图片大于800K，请重新上传')
      }
      return isLt10M
    },
    httpRequest (type, upload) { // oss直传图片
      console.log('httpRequest')
      let formData = new FormData()
      formData.append('image', upload.file)
      formData.append('url', true)
      // 其他参数
      formData.append('token', window.localStorage.token || '')
      let config = {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
      if (type === 'avatar') {
        this.form.avatar.uploading = true
        this.form.avatar.uploadError = false
        this.form.avatar.uploadTip = '正在上传...'
        this.form.avatar.url = URL.createObjectURL(upload.file)
      } else if (type === 'majorcertificate') {
        this.form.majorcertificate.uploading = true
        this.form.majorcertificate.uploadError = false
        this.form.majorcertificate.uploadTip = '正在上传...'
        this.form.majorcertificate.url = URL.createObjectURL(upload.file)
      } else if (type === 'basicmusiccertificate') {
        this.form.basicmusiccertificate.uploading = true
        this.form.basicmusiccertificate.uploadError = false
        this.form.basicmusiccertificate.uploadTip = '正在上传...'
        this.form.basicmusiccertificate.url = URL.createObjectURL(upload.file)
      }
      axios.post('/upload', formData, config).then(res => {
        console.log('uploadSuccess', res)
        if (type === 'avatar') {
          this.form.avatar.uploadTip = ''
          this.form.avatar.uploading = false
          this.form.avatar.id = res.data.id[0]
          this.form.avatar.valid = true
          // this.form.avatar.url = res.data.url
        } else if (type === 'majorcertificate') {
          this.form.majorcertificate.uploadTip = ''
          this.form.majorcertificate.uploading = false
          this.form.majorcertificate.id = res.data.id[0]
          this.form.majorcertificate.valid = true
          // this.form.majorcertificate.url = res.data.url
        } else if (type === 'basicmusiccertificate') {
          this.form.basicmusiccertificate.uploadTip = ''
          this.form.basicmusiccertificate.uploading = false
          this.form.basicmusiccertificate.id = res.data.id[0]
          this.form.basicmusiccertificate.valid = true
          // this.form.basicmusiccertificate.url = res.data.url
        }
      }).catch(err => {
        if (type === 'avatar') {
          this.form.avatar.uploading = false
          this.form.avatar.uploadError = true
          this.form.avatar.id = ''
          this.form.avatar.uploadTip = '上传失败！'
          this.form.avatar.valid = false
        } else if (type === 'majorcertificate') {
          this.form.majorcertificate.uploading = false
          this.form.majorcertificate.uploadError = true
          this.form.majorcertificate.id = ''
          this.form.majorcertificate.uploadTip = '上传失败！'
          this.form.majorcertificate.valid = false
        } else if (type === 'basicmusiccertificate') {
          this.form.basicmusiccertificate.uploading = false
          this.form.basicmusiccertificate.uploadError = true
          this.form.basicmusiccertificate.id = ''
          this.form.basicmusiccertificate.uploadTip = '上传失败！'
          this.form.basicmusiccertificate.valid = false
        }
        console.log(err)
      })
    },
    inputEnter: function (type, e) {
      let { value } = e.target
      console.log(type, value)
      if (type === 'name') { // 姓名
        let pinyin = ''
        if (value && Mtils.validation.isChinese(value)) { // 全中文
          pinyin = Mtils.utils.makePy(value)
          if (value && value.length) {
            let newStr = ''
            for (let i = 0; i < value.length; i++) {
              newStr += ((i === 0) ? value[i] : (' ' + value[i]))
            }
            pinyin = Mtils.utils.makePy(newStr)
          }
        } else { // 非全中文
          pinyin = this.titleCase(value)
        }
        console.log('pinyin', pinyin)
        this.form.name.value = value
        this.form.name.valid = Boolean(value)
        this.form.pinyin.value = pinyin
        this.form.pinyin.valid = Boolean(pinyin)
      }
    },
    inputChange: function (type, e) {
      let { value } = e.target
      if (type === 'pinyin' || type === 'cardnumber' || type === 'phone' || type === 'bent1' || type === 'bent2' || type === 'bent3' || type === 'bent4' || type === 'bent5' || type === 'contact' || type === 'contactphone' || type === 'fillter' || type === 'teacher' || type === 'teacherphone') { // 拼音、证件号码、手机号、考试曲目、联系人、联系人电话、填表人、指导老师、指导老师电话
        this.form[type].value = value
        this.form[type].text = value
        this.form[type].valid = Boolean(value)
      }
    },
    titleCase: function (str) {
      let subStrArr = str.toLowerCase().split(/\s+/)
      for (let i = 0; i < subStrArr.length; i++) {
        subStrArr[i] = subStrArr[i].slice(0, 1).toUpperCase() + subStrArr[i].slice(1)
      }
      return subStrArr.join(' ')
    },
    cardnumberInput (e) {
      let eve = e || window.event
      let val = eve.currentTarget.value
      let numVal = val.replace(/[^(0-9|x)]/ig, '')
      this.form.cardnumber.value = numVal
      this.form.cardnumber.text = numVal
      this.form.cardnumber.valid = Boolean(numVal)
    },
    phoneInput (e) {
      let eve = e || window.event
      let val = eve.currentTarget.value
      let numVal = val.replace(/[^0-9]/ig, '')
      this.form.phone.value = numVal
      this.form.phone.text = numVal
      this.form.phone.valid = Boolean(numVal)
    },
    teacherPhoneInput (e) {
      let eve = e || window.event
      let val = eve.currentTarget.value
      let numVal = val.replace(/[^0-9]/ig, '')
      this.form.teacherphone.value = numVal
      this.form.teacherphone.text = numVal
      this.form.teacherphone.valid = Boolean(numVal)
    },
    pickerChange: function (type, value) {
      console.log('pickerChange', this.form.nationality, type, value)
      if (type === 'birthday') { // 出生日期
        this.form.birthday.value = value
        this.form.birthday.text = value
        this.form.birthday.valid = Boolean(value)
      } else if (type === 'nationality') { // 国籍
        this.form.nationality.value = value
        this.form.nationality.text = value
        this.form.nationality.valid = Boolean(value)
      } else if (type === 'volk') {
        this.form.volk.value = value
        this.form.volk.text = value
        this.form.volk.valid = Boolean(value)
      } else if (type === 'cardtype') {
        this.form.cardtype.value = value
        this.form.cardtype.text = value
        this.form.cardtype.valid = Boolean(value)
      } else if (type === 'major') {
        let levels = this.majors.filter(item => item.value === value)[0].levels
        let continuitys = []
        this.levels = levels
        this.continuitys = continuitys
        this.form.major.value = value
        this.form.major.text = value
        this.form.major.valid = Boolean(value)
        this.form.level.value = ''
        this.form.level.text = ''
        this.form.level.valid = false
        this.form.continuity.value = ''
        this.form.continuity.text = ''
        this.form.continuity.valid = false
        this.form.majorcertificate.required = false
        this.form.basicmusiccertificate.required = false
        this.form.lastgetcertificate.required = false
        this.form.lastgetcertificate.year.required = false
        this.form.lastgetcertificate.month.required = false
        this.form.bent1.required = Boolean(value !== '基本乐科')
        this.form.bent2.required = Boolean(value !== '基本乐科')
      } else if (type === 'level') {
        let levels = JSON.parse(JSON.stringify(this.levels))
        let idx = levels.indexOf(value)
        let major = this.form.major
        let continuitys = []
        if (major.text === '基本乐科' && idx !== (levels.length - 1)) { // 判断是否基本乐科、且不是最后一级
          continuitys = [
            {
              value: '0',
              text: '否'
            },
            {
              value: levels[idx + 1],
              text: levels[idx + 1]
            }
          ]
        }
        let needMajorCer = false
        let needBasicMusicCer = false
        if (major.value !== '基本乐科' && idx > 8) { // 报考演唱演奏10级，需上传本考级机构同专业9级证书；报考表演文凭级，需上传本考级机构同专业10级证书
          needMajorCer = true
        }
        if ((major.value !== '基本乐科' && idx > 1) || (major.value === '基本乐科' && idx > 0)) { // 非基本乐科3级及以上、基本乐科2级及以上
          needBasicMusicCer = true
        }
        this.continuitys = continuitys
        this.form.continuity.required = Boolean(continuitys && continuitys[0])
        this.form.continuity.value = ''
        this.form.continuity.text = ''
        this.form.continuity.valid = false
        this.form.level.value = value
        this.form.level.text = value
        this.form.level.valid = Boolean(value)
        this.form.majorcertificate.required = needMajorCer
        this.form.basicmusiccertificate.required = needBasicMusicCer
        this.form.lastgetcertificate.required = needMajorCer
        this.form.lastgetcertificate.year.required = needMajorCer
        this.form.lastgetcertificate.year.required = needMajorCer
        console.log('continuitys', continuitys)
      } else if (type === 'continuity') {
        let selected = this.continuitys.filter(item => item.value === value)[0]
        this.form.continuity.value = selected.value
        this.form.continuity.text = selected.text
        this.form.continuity.valid = true
      } else if (type === 'lastgetyear') { // 最近一次获得同专业考级证书年份
        let selected = this.years.filter(item => item.value === value)[0]
        this.form.lastgetcertificate.year.value = selected.value
        this.form.lastgetcertificate.year.text = selected.text
        this.form.lastgetcertificate.year.valid = true
      } else if (type === 'lastgetmonth') { // 最近一次获得同专业考级证书月份
        let selected = this.months.filter(item => item.value === value)[0]
        this.form.lastgetcertificate.month.value = selected.value
        this.form.lastgetcertificate.month.text = selected.text
        this.form.lastgetcertificate.month.valid = true
      }
    },
    genderChange: function (value) {
      console.log('genderChange', value)
      this.form.gender.valid = Boolean(value)
    },
    validateFormData: function () {
      let form = JSON.parse(JSON.stringify(this.form))
      let valid = true
      for (let key in form) {
        console.log('key', key)
        if (key === 'lastgetcertificate') { // 最近一次获得同专业考级证书
          if (form.lastgetcertificate.required) {
            if (!form.lastgetcertificate.year.valid && form.lastgetcertificate.year.required) {
              // this.scrollToBlock(key)
              if (form.lastgetcertificate.year.tip) {
                this.$toast(form.lastgetcertificate.year.tip)
              }
              valid = false
              break
            } else if (!form.lastgetcertificate.month.valid && form.lastgetcertificate.month.required) {
              // this.scrollToBlock(key)
              if (form.lastgetcertificate.month.tip) {
                this.$toast(form.lastgetcertificate.month.tip)
              }
              valid = false
              break
            }
          }
        } else {
          if (form[key].required && !form[key].valid) { // 必填且无效
            // this.scrollToBlock(key)
            if (form[key].tip) {
              this.$toast(form[key].tip)
            }
            valid = false
            break
          }
        }
      }
      if (valid) {
        let formData = {}
        formData.exam_id = this.exam_id
        formData.picture_id = this.form.avatar.id
        formData.name = this.form.name.value
        formData.pinyin = this.form.pinyin.value
        formData.sex = this.genderText[this.form.gender.value]
        formData.birth = this.form.birthday.value
        formData.nationality = this.form.nationality.value
        formData.nation = this.form.volk.value
        formData.id_type = this.form.cardtype.value
        formData.id_number = this.form.cardnumber.value
        formData.phone = this.form.phone.value
        formData.domain = this.form.major.value
        formData.level = this.form.level.value
        formData.continuous_level = this.form.continuity.text
        formData.lately_credential = this.form.lastgetcertificate.required ? (this.form.lastgetcertificate.year.value + ',' + this.form.lastgetcertificate.month.value + ',' + this.form.major.value + ',' + this.form.level.value.replace('级', '')) : ''
        formData.pro_certificate_id = this.form.majorcertificate.required ? this.form.majorcertificate.id : ''
        formData.basic_certificate_id = this.form.basicmusiccertificate.required ? this.form.basicmusiccertificate.id : ''
        formData.track_one = this.form.major.value === '基本乐科' ? '无' : this.form.bent1.value
        formData.track_two = this.form.major.value === '基本乐科' ? '无' : this.form.bent2.value
        formData.track_three = this.form.major.value === '基本乐科' ? '' : this.form.bent3.value
        formData.track_four = this.form.major.value === '基本乐科' ? '' : this.form.bent4.value
        formData.track_five = this.form.major.value === '基本乐科' ? '' : this.form.bent5.value
        formData.preparer = this.form.fillter.value
        formData.adviser = this.form.teacher.value
        formData.adviser_phone = this.form.teacherphone.value
        return formData
      } else {
        return valid
      }
    },
    saveForm: function () {
      let {
        form,
        levels,
        continuitys
      } = this
      let _form = JSON.parse(JSON.stringify(form))
      let _levels = JSON.parse(JSON.stringify(levels))
      let _continuitys = JSON.parse(JSON.stringify(continuitys))
      let applyForm = {}
      for (let key1 in _form) {
        if (key1 === 'avatar' || key1 === 'majorcertificate' || key1 === 'basicmusiccertificate' || key1 === 'major' || key1 === 'level' || key1 === 'lastgetcertificate' || key1 === 'continuity') {
          continue
        }
        applyForm[key1] = {}
        for (let key2 in _form[key1]) {
          if (key2 === 'required' || key2 === 'valid' || key2 === 'value' || key2 === 'idx' || key2 === 'id' || key2 === 'text' || key2 === 'url' || key2 === 'month' || key2 === 'year') {
            applyForm[key1][key2] = _form[key1][key2]
          }
        }
      }
      applyForm = JSON.stringify(applyForm)
      window.localStorage.applyForm = applyForm
      window.localStorage.applyFormOptions = JSON.stringify({
        levels: _levels,
        continuitys: _continuitys
      })
      this.$toast('保存成功')
      if (this.saveTimer) {
        clearTimeout(this.saveTimer)
      }
      this.saveTimer = setTimeout(() => {
        this.$router.go(-1)
      }, 2000)
    },
    getForm: function () {
      let applyForm = window.localStorage.applyForm ? JSON.parse(window.localStorage.applyForm) : {}
      let applyFormOptions = window.localStorage.applyFormOptions ? JSON.parse(window.localStorage.applyFormOptions) : {}
      console.log('applyForm', applyForm)
      let {
        form
      } = this
      let _form = JSON.parse(JSON.stringify(form))
      let {
        levels,
        continuitys
      } = applyFormOptions
      let newForm = {}
      for (let key in _form) {
        newForm[key] = Object.assign({}, _form[key], applyForm[key])
      }
      console.log('getForm', _form, newForm)
      if (applyForm) {
        this.form = newForm
        this.levels = levels || []
        this.continuitys = continuitys || []
      }
    },
    submitTap: function () {
      let formData = this.validateFormData()
      if (formData) { // 数据填写完毕
        if (this.submitting) { // 正在提交
          this.$toast('正在提交...')
          return false
        }
        this.submitting = true
        this.$ajax('/apply/add', { data: formData }).then(res => {
          this.submitting = false
          if (res && (res.error === 0 || res.error) && res.data) { // 提交表单成功
            this.submitSuccess(res.data.id)
          }
        }).catch(err => {
          console.log('err', err)
          this.submitting = false
        })
      }
    },
    submitSuccess: function (id) {
      window.localStorage.applyForm = ''
      window.localStorage.applyFormOptions = ''
      this.form = initialForm
      let { roles, storeUD } = this
      if (storeUD.userType === roles.teacher || storeUD.userType === roles.institution) { // 老师或机构,直接成功，无需审核、支付
        this.$router.replace({ path: '/enroll/detail?id=' + id })
        return false
      }
      if ((this.form.majorcertificate.required && this.form.majorcertificate.valid) || (this.form.basicmusiccertificate.required && this.form.basicmusiccertificate.valid)) { // 需上传基本乐科证书或者专业证书，需审核
        this.$router.replace({ path: '/enroll/applysuccess?id=' + id })
      } else {
        this.$router.replace({ path: '/enroll/detail?id=' + id })
      }
    }
  }
}
</script>

<style scoped>
.p-enroll-apply{}
.enroll-apply-body{
  padding: 9px 34px;
  background: #fff;
}
.form-box{
  padding: 18px 0;
}
.yk-required{
  font-size: 12px;
  color: #D0021B;
  margin-right: 2px;
}
.required-title{
  font-size: 16px;
  line-height: 22px;
  color: #141619;
  padding-bottom: 16px;
}
.avatar-image{
  width: 150px;
  height: 227px;
  margin: 0 40px 0 65px;
  background: url('../assets/image/default_avatar.png') no-repeat;
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center;
  background-color: #EEEEEE;
  transition: background-image 0.4s;
  position: relative;
}
.image-tip{
  background: rgba(0,0,0,0.6);
  font-size: 16px;
  color: #fff;
  text-align: center;
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
}
.state-text{
  font-size: 16px;
  line-height: 22px;
  position: relative;
}
.yk-warning{
  color: #D0021B;
  position: absolute;
  left: 0;
  top: 0;
}
.warining-text{
  color: #D0021B;
  padding-left: 24px;
}
.avatar-btn{
  width: 78px;
  height: 38px;
  line-height: 38px;
  font-size: 16px;
  border: 1px solid #979797;
  border-radius: 5px;
  text-align: center;
  color: #000;
}
.avatar-btn:hover{
  font-weight: bold;
}
.pinyin-btn{
  width: 78px;
  height: 38px;
  line-height: 38px;
  font-size: 16px;
  border: 1px solid #979797;
  border-radius: 5px;
  text-align: center;
  color: #000;
  margin-left: 70px;
}
.pinyin-btn:hover{
  font-weight: bold;
}
.full-input{
  width: 458px;
  height: 38px;
  line-height: 38px;
  border: 1px solid #979797;
  text-indent: 14px;
  font-size: 16px;
  color: #141619;
}
.full-input:disabled{
  background: #979797;
}
.gender-label{
  padding: 0 20px;
  margin: 0 24px;
  font-size: 16px;
  line-height: 22px;
}
.gender-radio{
  display: block;
  margin: 1px 10px 0 0;
  width: 16px;
  height: 16px;
  padding: 0;
}
.select-picker /deep/ .el-input__inner{
  width: 458px;
  height: 38px;
  line-height: 38px;
  border: 1px solid #979797;
  text-indent: 14px;
  border-radius: 0;
  padding: 0;
  font-size: 16px;
  color: #141619;
}
.short-select-picker /deep/ .el-input__inner{
  width: 358px;
  height: 38px;
  line-height: 38px;
  border: 1px solid #979797;
  text-indent: 14px;
  border-radius: 0;
  padding: 0;
  font-size: 16px;
  color: #141619;
}
.short-select-picker2{
  margin-left: 40px;
}
.short-select-picker2:first-child{
  margin-left: 0;
}
.short-select-picker2 /deep/ .el-input__inner:disabled{
  background: #979797;
}
.short-select-picker2 /deep/ .el-input__inner:disabled:hover{
  border: 1px solid #979797;
}
.short-select-picker2 /deep/ .el-input__inner{
  width: 158px;
  height: 38px;
  line-height: 38px;
  border: 1px solid #979797;
  text-indent: 14px;
  border-radius: 0;
  padding: 0;
  font-size: 16px;
  color: #141619;
}
.short-select-picker2.disabled /deep/ .el-input__suffix{
  display: none;
}
.line-h{
  width: 100%;
  height: 1px;
  background-color: #979797;
  margin: 20px 0;
}
.certificate-image-box{
  width: 360px;
  height: 240px;
  background: #EEEEEE;
  background: url('../assets/image/default_avatar.png') no-repeat;
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center;
  background-color: #EEEEEE;
  transition: background-image 0.4s;
  position: relative;
}
.bottom-buttons{
  padding: 35px 34px 85px;
}
.buttons-box{
  width: 350px;
  margin: 40px auto 0;
}
.save-button{
  width: 150px;
  height: 40px;
  font-size: 18px;
  line-height: 40px;
  color: #8B572A;
  background: #F2E0CA;
  border-radius: 5px;
  text-align: center;
}
.submit-button{
  width: 150px;
  height: 40px;
  font-size: 18px;
  line-height: 40px;
  color: #ffffff;
  background: #108EE9;
  border-radius: 5px;
  text-align: center;
}
</style>
