import React from 'react';
import classes from './Auth.css';
import {login} from "../../services/ApiService";
import Input from "../Base/Input/Input";
import Button from "../Base/Button/Button";

export default class Auth extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            username:        '',
            password:        '',
            usernameTouched: false,
            passwordTouched: false,
            formError:       null,
        };

        this.submitHandler = this.submitHandler.bind(this);
    }

    submitHandler(e) {
        e.preventDefault();

        this.setState({
            formError: null,
        });

        login(this.state.username, this.state.password)
            .then(() => {
                this.props.onLoggedIn();
            })
            .catch((e) => this.setState({formError: e.response.data}))
        ;
    };

    render() {
        return (
            <div className={classes.Auth}>
                <form onSubmit={this.submitHandler}>
                    <Input
                        label={'Login'}
                        touched={this.state.usernameTouched}
                        value={this.state.username}
                        valid={this.state.username.trim() !== ''}
                        errorMessage={'Enter valid login'}
                        onChange={e => this.setState({username: e.target.value, usernameTouched: true})}
                    />

                    <Input
                        type={'password'}
                        label={'Password'}
                        touched={this.state.passwordTouched}
                        value={this.state.password}
                        valid={this.state.password.trim() !== ''}
                        errorMessage={'Enter valid password'}
                        onChange={e => this.setState({password: e.target.value, passwordTouched: true})}
                    />

                    {
                        null !== this.state.formError
                            ? <div className={classes.Error}>{this.state.formError}</div>
                            : null
                    }

                    <Button type={'primary'} onClick={this.submitHandler} disabled={false}>Login</Button>
                </form>
            </div>
        );
    }
}
