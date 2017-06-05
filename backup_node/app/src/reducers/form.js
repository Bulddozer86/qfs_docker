import {createStore} from 'redux';
import {combineForms} from 'react-redux-form'

const initialState = {
  name: '',
  list: []
};

export default function formStore(state = initialState, action) {
  console.log(action.type);
  return createStore(combineForms({
    user: state,
  }));
}
