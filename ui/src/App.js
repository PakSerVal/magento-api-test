import React from "react";
import Auth from "./components/Auth/Auth";
import Customers from "./components/Customers/Customers";
import {isLoggedIn} from "./services/ApiService";

export default class App extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            isLoggedIn: false,
        };

        this.logInHandler = this.logInHandler.bind(this);
    }

    componentDidMount() {
        this.setState({
            isLoggedIn: isLoggedIn(),
        });
    }

    logInHandler() {
        this.setState({
            isLoggedIn: isLoggedIn(),
        });
    }

    render() {
        return <React.Fragment>
            {
                this.state.isLoggedIn
                    ? <Customers/>
                    : <Auth onLoggedIn={this.logInHandler} />
            }
        </React.Fragment>;
    }
}
