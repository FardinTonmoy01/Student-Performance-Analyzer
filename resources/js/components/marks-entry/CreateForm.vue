<!-- resources/js/components/EntryForm.vue -->
<template>
  <div class="col-md-12">
            <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Marks Entry</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form @submit.prevent="submitForm()" @reset.prevent="reset" autocomplete="off">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="form-group">

                          <label class="form-label">Insitute <span class="text-danger">*</span></label>
                          <b-form-select
                            v-model="formData.institute_id"
                            plain
                            :disabled="formData.details.length ? true : false"
                            :required="true"
                            :options="instituteList">
                            <template #first>
                              <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                            </template>
                          </b-form-select>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Year <span class="text-danger">*</span></label>
                          <input type="number"
                          v-model="formData.year"
                          :disabled="formData.details.length ? true : false"
                          name="year" maxlength="4"
                          class="form-control" :required="true" placeholder="Year">
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Class <span class="text-danger">*</span></label>
                          <b-form-select
                            v-model="formData.class_id"
                            plain
                            :required="true"
                            :disabled="formData.details.length ? true : false"
                            :options="classList">
                            <template #first>
                              <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                            </template>
                          </b-form-select>
                        </div>
                      </div>


                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Group <span v-if="restricted" class="text-danger">*</span></label>
                          <b-form-select
                            v-model="formData.group_id"
                            plain
                            :required="restricted"
                            :disabled="formData.details.length ? true : false"
                            :options="groupList">
                            <template #first>
                              <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                            </template>
                          </b-form-select>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Section <span class="text-danger">*</span></label>
                          <b-form-select
                            v-model="formData.section_id"
                            plain
                            :required="true"
                            :disabled="formData.details.length ? true : false"
                            :options="sectionList">
                            <template #first>
                              <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                            </template>
                          </b-form-select>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Shift <span class="text-danger">*</span></label>
                          <b-form-select
                            v-model="formData.shift_id"
                            plain
                            :required="true"
                            :disabled="formData.details.length ? true : false"
                            :options="shiftList">
                            <template #first>
                              <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                            </template>
                          </b-form-select>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Indicator <span class="text-danger">*</span></label>
                          <b-form-select
                            v-model="formData.indicator_id"
                            plain
                            name="indicator_id"
                            :required="true"
                            :disabled="formData.details.length ? true : false"
                            @change="fetchTotalMarks()"
                            :options="indicatorList">
                            <template #first>
                              <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                            </template>
                          </b-form-select>
                        </div>
                      </div>
                    </div>

                    <hr>

                    <!-- Detail Form -->
                    <div class="row">
                      <!-- <div class="col-md-12">
                        <h5>Marks Entry</h5>
                      </div> -->
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Student <span class="text-danger">*</span></label>
                          <b-form-select
                            v-model="detailForm.student_id"
                            plain
                            :options="studentList">
                            <template #first>
                              <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                            </template>
                          </b-form-select>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Subject <span class="text-danger">*</span></label>
                          <b-form-select
                            v-model="detailForm.subject_id"
                            plain
                            @change="fetchTotalMarks()"
                            :options="subjectList">
                            <template #first>
                              <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                            </template>
                          </b-form-select>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Total Marks <span class="text-danger">*</span></label>
                          <input
                              type="number"
                              v-model="detailForm.total_marks"
                              readonly
                              size="100"
                              class="form-control"
                            />
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Obtain Marks <span class="text-danger">*</span></label>
                          <input
                              type="number"
                              v-model="detailForm.obtain_marks"
                              @input="marksValidation()"
                              maxlength="3"
                              size="100"
                              class="form-control"
                            />
                        </div>
                      </div>

                      <div class="col-sm-12">
                          <button type="button" @click="addMore()" class="btn btn-success btn-sm">Add</button>
                      </div>


                    </div>
                    <br>
                    <!-- Table Body -->
                    <div class="row">
                      <div class="col-md-12">
                        <table class="table table-sm">
                          <thead>
                            <tr>
                              <th class="text-center" scope="col" width="8%">SL</th>
                              <th scope="col" width="25%">Student Name</th>
                              <th scope="col">Subject</th>
                              <!-- <th scope="col">Indicator</th> -->
                              <th scope="col" width="10%">Total Marks</th>
                              <th scope="col" width="10%">Obtain Marks</th>
                              <th class="text-center" scope="col" width="10%">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                              <tr v-for="(item, index) in formData.details" :key="index">
                                  <td class="text-center">{{ index + 1 }}</td>
                                  <td>{{ studentList.find(el => el.value == item.student_id).text ?? '' }}</td>
                                  <td>{{ subjectList.find(el => el.value == item.subject_id).text ?? '' }}</td>
                                  <!-- <td>{{ indicatorList.find(el => el.value == item.indicator_id).text ?? '' }}</td> -->
                                  <td>{{ item.total_marks }}</td>
                                  <td>{{ item.obtain_marks }}</td>
                                  <td class="text-center">
                                    <button type="button" @click="deleteIndex(index)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                  </td>
                              </tr>
                              <tr v-if="!formData?.details?.length">
                                <td colspan="7" class="text-center">No Data Found</td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" :disabled="loader" class="btn btn-primary">{{ editId ? 'Update' : 'Submit' }}</button>
                  </div>
                </form>
              </div>
            <!-- /.card -->
            </div>
</template>

<script>
export default {
  data() {
    return {
      allGroupList: [],
      allSectionList: [],
      classList: [],
      groupList: [],
      instituteList: [],
      studentList: [],
      sectionList: [],
      shiftList: [],
      indicatorList: [],
      restricted: false,
      formData: {
        institute_id: null,
        class_id: null,
        group_id: null,
        year: '',
        section_id: null,
        shift_id: null,
        indicator_id: null,
        details: []
      },
      detailForm: {
        student_id: null,
        subject_id: null,
        obtain_marks: '',
        total_marks: ''
      },
      editId: 0,
      subjectList: [],
      loader: false,
    };
  },
  watch: {
    "formData.class_id": function (newVal, oldVal) {
      if (newVal !==  oldVal) {
        this.getGroupList()
        this.sectionList = this.getSectionList(newVal, 0)
      }
    },
    "formData.group_id": function (newVal, oldVal) {
      if (newVal !==  oldVal && newVal) {
        this.loadClassWiseSubjects()
        this.sectionList = this.getSectionList(this.formData.class_id, newVal)
      } else {
        this.sectionList = this.getSectionList(this.formData.class_id, 0)
      }
    },
    "formData.shift_id": function (newVal, oldVal) {
      if (newVal !==  oldVal && newVal) {
        this.loadShiftWiseStudents()
      }
    }
  },
  computed: {
  },
  mounted () {
    const editId = this.$route.params.id;
    if (editId) {
      this.editId = editId
      this.getEditData()
    }
  },
  created () {
    this.fetchInstitutes()
    this.fetchClasses()
    this.fetchGroups()
    this.fetchSections()
    this.fetchShifts()
    this.fetchIndicators()
  },
  methods: {
    deleteIndex (index) {
      this.formData.details.splice(index, 1);
    },
    addMore () {
        console.log('detailsForm', this.detailForm)
        if (this.detailForm.subject_id && this.detailForm.student_id && this.detailForm.obtain_marks) {
          // const result = this.formData.details.find(el => el.student_id == this.detailForm.student_id && el.subject_id == this.detailForm.subject_id)
          // if (typeof result !== 'undefined') {
          //   this.$toast.error({
          //       title: '!',
          //       message: 'Item already added!',
          //       color: '#ee5253'
          //   })
          // } else {
            this.formData.details.push(this.detailForm);
            this.detailForm = {
              student_id: this.detailForm.student_id,
              subject_id: null,
              obtain_marks: '',
              total_marks: ''
            }
          // }
        } else {
            this.$toast.error({
                title: '!',
                message: 'Please fill up the required field!',
                color: '#ee5253'
            })
        }
    },
    marksValidation () {
      const ObtainMarks = this.detailForm.obtain_marks ? parseInt(this.detailForm.obtain_marks) : 0
      const totalMarks = this.detailForm.total_marks ? parseInt(this.detailForm.total_marks) : 0
      if (totalMarks && ObtainMarks) {
        if (totalMarks >= ObtainMarks) {
          this.detailForm.obtain_marks = ObtainMarks
        } else {
          this.detailForm.obtain_marks = ''
        }
      } else {
        this.detailForm.obtain_marks = ''
      }
    },
    fetchTotalMarks () {
      if (this.formData.indicator_id && this.detailForm.subject_id && this.formData.year && this.formData.class_id) {
          const params = {
              params: {
                  year: this.formData.year,
                  class_id: this.formData.class_id,
                  group_id: this.formData.group_id,
                  subject_id: this.detailForm.subject_id,
                  indicator_id: this.formData.indicator_id
              }
          };
          axios.get(`/common-api/subject-wise-total-marks`, params).then(({data}) => {
              if(data) {
                  this.detailForm.total_marks = data.marks
              } else {
                this.detailForm.total_marks = ''
                this.detailForm.obtain_marks = ''
              }
          })
          .catch(error => console.log(error));
      }
    },
    getSectionList (classId, groupId = 0) {
      if (classId && !groupId) {
        return this.allSectionList.filter(el => el.class_id == classId)
      } else if (classId && groupId) {
        return this.allSectionList.filter(el => el.class_id == classId && el.group_id == groupId)
      } else {
        return []
      }
    },
    getEditData () {
      axios.get(`/marks-entry/show/${this.editId}`).then(({data}) => {
          if (data) {
            this.formData = data;
          } else {
            this.$toast.error({
                title: '!',
                message: 'Something went wrong!',
                color: '#ee5253'
            })
          }
      })
      .catch(error => console.log(error));
    },
    loadClassWiseSubjects() {
      const params = {
          params: {
              institute_id: this.formData.institute_id,
              year: this.formData.year,
              class_id: this.formData.class_id,
              group_id: this.formData.group_id
          }
      };
      axios.get(`/common-api/class-wise-subjects`, params).then(({data}) => {
          if(data.length) {
              this.subjectList = data.map(el => {
                return Object.assign({}, el, { text: el.subject_name, value: el.id })
              })
          } else {
            this.subjectList = []
          }
      })
      .catch(error => console.log(error));
    },
    loadShiftWiseStudents() {
      const params = {
          params: {
              institute_id: this.formData.institute_id,
              year: this.formData.year,
              class_id: this.formData.class_id,
              group_id: this.formData.group_id,
              section_id: this.formData.section_id,
              shift_id: this.formData.shift_id
          }
      };
      axios.get(`/common-api/class-wise-students`, params).then(({data}) => {
          if(data.length) {
              this.studentList = data
          }
      })
      .catch(error => console.log(error));
    },
    fetchIndicators() {
      axios.get(`/common-api/indicators`).then(({data}) => {
          this.indicatorList = data.map(el => {
            return Object.assign({}, el, { text: el.indicator_name, value: el.id })
          });
      })
      .catch(error => console.log(error));
    },
    getGroupList () {
      const List = this.allGroupList.filter(el => el.class_id == this.formData.class_id)
      if (List.length) {
        this.restricted = true;
        this.groupList = List
      } else {
        this.groupList = []
        this.restricted = false
        this.loadClassWiseSubjects()
      }
    },
    fetchInstitutes () {
      axios.get(`/common-api/institutes`).then(({data}) => {
          this.instituteList = data;
      })
      .catch(error => console.log(error));
    },
    fetchClasses () {
      axios.get(`/common-api/classes`).then(({data}) => {
          this.classList = data;
      })
      .catch(error => console.log(error));
    },
    fetchGroups () {
      axios.get(`/common-api/groups`).then(({data}) => {
          this.allGroupList = data;
      })
      .catch(error => console.log(error));
    },
    fetchSections () {
      axios.get(`/common-api/sections`).then(({data}) => {
          this.allSectionList = data;
      })
      .catch(error => console.log(error));
    },
    fetchShifts () {
      axios.get(`/common-api/shifts`).then(({data}) => {
          this.shiftList = data;
      })
      .catch(error => console.log(error));
    },
    submitForm() {
        if (!this.formData.details.length) {
            return this.$toast.error({
              title: '!',
              message: 'Add at least one item in the list!',
              color: '#ee5253'
            })
        }
        this.loader = true;
        axios.post(`/marks-entry/store`, this.formData).then(response => {
          if (response.data.success) {
            this.$toast.success({
              title: 'Success',
              message: response.data.message,
              color: '#D6E09B'
            })
            window.location.href = '/marks-entry/list'
          } else {
            console.log('succe fallse', response)
            this.$toast.success({
              title: '!',
              message: response.data.message,
              color: '#ee5253'
            })
          }
        })
        .catch(error => {
          console.log('error', error.response)
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                // const result = res.responseJSON;
                $.each(errors, function(field_name, error){
                    $(document).find('[name='+field_name+']').after('<div class="text-strong text-danger w-100">' +error+ '</div>')
                })

                this.$toast.error({
                    title: '!',
                    message: 'Something went wrong!',
                    color: '#ee5253'
                })

                this.loader = false;
                return;
            }
        })
    }
  },
};
</script>

<style scoped>
/* Your component styles go here */
</style>