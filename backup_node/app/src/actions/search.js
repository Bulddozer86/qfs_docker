import fetch from 'isomorphic-fetch';
import {SET_SEARCH_VALUE} from '../consts/page-consts'

export function fetchSearchData() {
    return fetch('http://localhost:8000', {
      method: 'GET',
      mode: 'cors'
    }).then(res => res.json())
      .catch(err => err);

}
