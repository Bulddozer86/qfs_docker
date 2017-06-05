import React, {Component} from 'react'
// import {bigActionCreator} from 'redux'
// import {connect} from 'react-redux'
// import * as fetchSearchData from '../actions/search';

class SearchList extends Component {
    // constructor(props) {
    //     super(props);
    //
    //     this.state = {
    //         searchData: []
    //     }
    // };

    // componentDidMount() {
    //     fetchSearchData()
    //         .then((data) => {
    //             this.setState(state => {
    //                 state.searchData = data;
    //                 return state;
    //             })
    //         })
    //         .catch((err) => {
    //             console.error('err', err);
    //         });
    // };


    render() {
        return (
            <table>
                <tbody>
                {this.props.search && this.props.search.map(s => {
                    //console.log(s);
                    return (
                        <tr key={s.id}>
                            <td>{s.price}</td>
                            <td>{s.headline}</td>
                        </tr>
                    )
                })}
                </tbody>
            </table>


        );
    }
}
