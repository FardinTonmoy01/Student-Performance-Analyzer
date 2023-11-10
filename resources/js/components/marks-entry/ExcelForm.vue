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
                            :options="indicatorList">
                            <template #first>
                              <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                            </template>
                          </b-form-select>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label"></label>
                          <div>
                            <button @click="downloadExcel()" :disabled="loader" type="button" class="btn btn-info mt-2">Export Excel</button>
                          </div>
                        </div>
                      </div>

                      <div v-if="uploadPermit" class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Import Excel <span class="text-danger">*</span></label>
                          <div class="input-group">
                              <input type="file" name="file" @change="uploadFile" ref="file" :required="true" id="photoFile">
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" :disabled="loader" class="btn btn-primary">{{ 'Submit' }}</button>
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
        file: ''
      },
      inputFile: '',
      subjectList: [],
      loader: false,
      uploadPermit: false
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
  created () {
    this.fetchInstitutes()
    this.fetchClasses()
    this.fetchGroups()
    this.fetchSections()
    this.fetchShifts()
    this.fetchIndicators()
  },
  methods: {
    uploadFile() {
        this.inputFile = this.$refs.file.files[0];
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
    downloadExcel () {
        const params = {
          params: {...this.formData},
          responseType: "blob"
        };
        axios.get(`/marks-entry/export-excel`, params).then(response => {
            // Create a Blob from the response data
            const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            this.uploadPermit = true;
            // Create a link element and trigger the download
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'marks.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })
        .catch(error => {
          this.$toast.error({
            title: '!',
            message: 'This indicator does not have marks settings!',
            color: '#ee5253'
          })
          console.log('error', error.response)
        });
    },
    submitForm() {
        this.loader = true;
        const formData = new FormData();
        // Using array methods
        Object.entries(this.formData).forEach(([key, value]) => {
            if (key == 'file') {
              formData.append(key, this.inputFile)
            } else {
              formData.append(key, value);
            }
        });

        axios.post(`/marks-entry/import-excel`, formData).then(response => {
          if (response.data.success) {
            this.$toast.success({
              title: 'Success',
              message: response.data.message,
              color: '#D6E09B'
            })
            window.location.href = '/marks-entry/list'
          } else {
            this.loader = false;
            this.$toast.error({
              title: '!',
              message: response.data.message,
              color: '#ee5253'
            })
          }
        })
        .catch(error => {
            this.loader = false;
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
            } else if (error.response.status == 500) {
              console.log('error 500', error.response)
                this.$toast.error({
                    title: '!',
                    message: error.response.data.message,
                    color: '#ee5253'
                })
            }
        })
    }
  },
};
</script>

<style scoped>
/* Your component styles go here */
</style>