<!-- resources/js/components/EntryForm.vue -->
<template>
  <div class="col-md-12">
            <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Marks Settings Entry</h3>
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
                          <input type="number" v-model="formData.year" name="year" maxlength="4" class="form-control" :required="true" placeholder="Year">
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
                    </div>
                    <br>
                    <!-- Table Body -->
                    <div class="row">
                      <div class="col-md-12">
                        <h5>Subject Wise Evaluating Indicators</h5>
                      </div>
                      <div class="col-md-12">
                        <table class="table table-sm">
                          <thead>
                            <tr>
                              <th scope="col">SL</th>
                              <th scope="col" width="15%">Subject Name</th>
                              <th v-for="(item, index) in indicators" :key="index" scope="col">{{ item.indicator_name }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(subject, index) in subjects" :key="index">
                              <td scope="row">{{ index + 1 }}</td>
                              <td>{{ subject.subject_name }}</td>
                              <td v-for="(indicator, index1) in subject.indicators" :key="index1">
                                <input
                                  type="number"
                                  v-model="indicator.marks"
                                  maxlength="3"
                                  size="100"
                                  class="form-control"
                                />
                              </td>
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
      classList: [],
      groupList: [],
      instituteList: [],
      allGroupList: [],
      restricted: false,
      formData: {
        institute_id: null,
        class_id: null,
        group_id: null,
        year: ''
      },
      editId: 0,
      indicators: [],
      subjects: [],
      loader: false,
    };
  },
  watch: {
    "formData.class_id": function (newVal, oldVal) {
      if (newVal !==  oldVal) {
        this.getGroupList()
      }
    },
    "formData.group_id": function (newVal, oldVal) {
      if (newVal !==  oldVal && newVal) {
        this.loadClassWiseSubjects()
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
    this.fetchIndicators()
  },
  methods: {
    getEditData () {
      axios.get(`/marks-settings/show/${this.editId}`).then(({data}) => {
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
    formatedSubject (data) {
        this.subjects = data.map((el, index) => {
          if (this.editId) {
            const filterIndicator = this.formData.indicators.filter(item => item.subject_id == el.id).map(el2 => {
              return Object.assign({}, el2, { id: el2.indicator_id });
            })
            if (filterIndicator.length) {
              return Object.assign({}, el, { indicators: [...filterIndicator] })
            }
          }

          const newIndic = this.indicators.map((item, index1) => {
            return Object.assign({}, item, { unique_id: index+index1 })
          })
          return Object.assign({}, el, { indicators: [...newIndic] })
        })
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
              this.formatedSubject(data)
          }
      })
      .catch(error => console.log(error));
    },
    fetchIndicators() {
      axios.get(`/common-api/indicators`).then(({data}) => {
          this.indicators = data;
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
    submitForm() {
        this.loader = true;

        let formData = {
          institute_id: this.formData.institute_id,
          class_id: this.formData.class_id,
          group_id: this.formData.group_id,
          year: this.formData.year,
          id: this.editId
        }

        formData.subjects = this.subjects

        let result = null;
        result = axios.post(`/marks-settings/store`, formData)

        result.then(response => {
          if (response.data.success) {
            this.$toast.success({
              title: 'Success',
              message: response.data.message,
              color: '#D6E09B'
            })
            window.location.href = '/marks-settings/list'
          } else {
            this.$toast.success({
              title: '!',
              message: response.data.message,
              color: '#ee5253'
            })
          }
        })
        .catch(error => {
          console.log('error', error)
            // if (error.response.status === 422) {
                // const errors = error.response.data.errors;
                this.$toast.error({
                    title: '!',
                    message: 'Something went wrong!',
                    color: '#ee5253'
                })

                this.loader = false;
                return;
            // }
            // this.$toast.error({
            //     title: '!',
            //     message: 'Something went wrong!',
            //     color: '#ee5253'
            // })
        })
    }
  },
};
</script>

<style scoped>
/* Your component styles go here */
</style>