<!-- resources/js/components/EntryForm.vue -->
<template>
  <div class="col-md-12">
            <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Marks Settings Details</h3>
                </div>
                <!-- /.card-header -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Insitute </label>
                          <div>{{ instituteList.find(el => el.value == formData.institute_id)?.text ?? '' }}</div>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Year </label>
                          <div>{{ formData.year }}</div>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Class </label>
                          <div>{{ classList.find(el => el.value == formData.class_id)?.text ?? '' }}</div>
                        </div>
                      </div>


                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="form-label">Group </label>
                          <div>{{ groupList.find(el => el.value == formData.group_id)?.text ?? '' }}</div>
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
                              <th scope="col" class="text-center">SL</th>
                              <th scope="col" width="15%">Subject Name</th>
                              <th class="text-center" v-for="(item, index) in indicators" :key="index" scope="col">{{ item.indicator_name }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(subject, index) in subjects" :key="index">
                              <td class="text-center" scope="row">{{ index + 1 }}</td>
                              <td>{{ subject.subject_name }}</td>
                              <td class="text-center" v-for="(indicator, index1) in subject.indicators" :key="index1">
                                {{ indicator.marks ?? 0 }}
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="button" @click="redirectTo()" class="btn btn-danger">{{ 'Cancel' }}</button>
                  </div>
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
      formData: {
        institute_id: null,
        class_id: null,
        group_id: null,
        year: ''
      },
      editId: 0,
      indicators: [],
      subjects: []
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
    redirectTo () {
      window.location.href = '/marks-settings/list'
    },
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
            const filterIndicator = this.formData.indicators.filter(item => item.subject_id == el.id)
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
        this.groupList = List
      } else {
        this.groupList = []
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
    }
  },
};
</script>

<style scoped>
/* Your component styles go here */
table {
  border: 1px solid #333 !important;
}
th, td {
  border: 1px solid #333 !important;
}
</style>