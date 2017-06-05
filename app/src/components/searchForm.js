import React, {PropTypes, Component} from 'react'
import {Control, Form} from 'react-redux-form';
import {fetchSearchData} from '../actions/search'

class SearchForm extends Component {
  handleSubmit(val) {
    fetchSearchData()
      .then((data) => {
        this.props.setList(state => {
          state.list = data;
          return state;
        })
      })
      .catch((err) => {
        console.error('err', err);
      });

    // Do anything you want with the form value
    // const state = this.state;
    // this.setState()
    // console.log(this.state);
  }

  render() {
    return (
      <Form model="user" onSubmit={(val) => this.handleSubmit(val)}>
        <label>Your name?</label>
        <Control.text model=".name"/>
        <button>Submit!</button>
      </Form>
    );
  }
}

// No need to connect()!
export default SearchForm;