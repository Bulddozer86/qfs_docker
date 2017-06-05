import React, {Component} from 'react'
import {bindActionCreators} from 'redux'
import {connect} from 'react-redux'
import {Provider} from 'react-redux';
import * as formActions from '../actions/formAction'

import SearchForm from '../components/searchForm'

class App extends Component {
  render() {
    const formStore = this.props.form;
    console.log(formStore);
    const {setList} = this.props.formActions;
    return (
      <Provider store={ formStore } >
        <SearchForm setList={setList} />
      </Provider>
    )
  }
}

function mapStateToProps(state) {
  console.log(state);
  return {
    form: state.searchForm
  }
}

function mapDispatchToProps(dispatch) {
  return {
    formActions: bindActionCreators(formActions, dispatch)
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(App)
