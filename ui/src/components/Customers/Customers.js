import React from 'react';
import classes from './Customers.css';
import {search as apiSearch} from "../../services/ApiService";
import Loader from 'react-loader-spinner'
import "react-loader-spinner/dist/loader/css/react-spinner-loader.css"

export default class Customers extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            customers:     [],
            isLoading:     false,
            searchStarted: false,
        };

        this.searchHandler = this.searchHandler.bind(this);
        this.search        = this.search.bind(this);
    }

    searchHandler(e) {
        if (!this.state.search) {
            this.setState({
                searchStarted: true,
            });
        }

        const q = e.target.value;

        this.search(q);
    }

    search(q) {
        this.setState({isLoading: true});

        clearTimeout(this.timer);
        this.timer = setTimeout(() => {
            apiSearch(q)
            .then((customers) => {
                this.setState({
                    customers,
                    isLoading: false,
                });
            })
            ;
        }, 500);
    }

    render() {
        return <div className={classes.Customers}>
            <h1 className={classes.Title}>Magento API customers search</h1>
            <input type="search" onChange={this.searchHandler} placeholder={'Type customer email...'} />
            {
                this.state.isLoading
                    ? <div className={classes.Loader}>
                        <Loader type="BallTriangle" color="#00BFFF" height={80} width={80} />
                    </div>
                    : (
                        this.state.customers.length !== 0
                                 ? <div className={classes.CustomersList}>
                                <div className={classes.Row}>
                                    <div className={classes.ColumnHeader}>Email</div>
                                    <div className={classes.ColumnHeader}>First Name</div>
                                    <div className={classes.ColumnHeader}>Last Name</div>
                                    <div className={classes.ColumnHeader}>Latest Order Number</div>
                                </div>
                                {
                                    this.state.customers.map((customer) => {
                                        return <div
                                            key={customer.id}
                                            className={classes.Row}
                                        >
                                            <div className={classes.Column}>{customer.email}</div>
                                            <div className={classes.Column}>{customer.firstName}</div>
                                            <div className={classes.Column}>{customer.lastName}</div>
                                            <div className={classes.Column}>{customer.latestOrderNum || '-'}</div>
                                        </div>
                                    })
                                }
                            </div>
                                 : (
                                this.state.searchStarted
                                    ? <h2 className={classes.EmptyResult}>Ooooops! Nothing found</h2>
                                    : null
                            )
                    )
            }
        </div>;
    }
}
