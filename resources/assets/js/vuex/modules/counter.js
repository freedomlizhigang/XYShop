import { INCREASE,COUNTTEST } from '.././mutation_types'

const state = {
  points: 10
}

const getters = {
  get_points: state => {
    return state.points
  }
}

const mutations = {
  [INCREASE](state, data){
    state.points = data
  },
  [COUNTTEST](state){
    state.points = 'TEST'
  }

}

export default {
  state,
  mutations,
  getters
}