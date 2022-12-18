import { __ } from '@wordpress/i18n';

import apiFetch from '@wordpress/api-fetch';

import { render, Component, Fragment, useState, useEffect } from '@wordpress/element';

import { HashRouter, Switch, Route, Link, NavLink } from 'react-router-dom';

import { TopBar } from '@common/top-bar';

import { routes } from '@data/onboarding-routes';

export const Onboarding = props => {

	let a = mailerglue_backend;

	const [state, setState] = useState( {
		email: '',
		password: '',
		loginEmail: '',
		loginPassword: '',
		activationCode: '',
		errors: [],
		sending: false,
		access_token: a.access_token,
		from_name: a.from_name,
		from_email: a.from_email,
	} );

	const updateState = (key, value) => {

		if ( key == 'array' ) {

			let object = value;

			setState( prevValues => {
				return { ...prevValues, ...object }
			} );

		} else {

			setState( prevValues => {
				return { ...prevValues, [key]: value }
			} );

		}

	}

	return (
		<>
			<TopBar />

			<div className="mailerglue-setup">

				<HashRouter>
					<Switch>
					{
						routes.map((route, i) => {
							return (
								<Route
									exact
									path={'/' + route.path}
									key={i}
									render={ (props) => <route.component state={state} setState={setState} updateState={updateState} /> }
								/>
							)
						})
					}
					</Switch>
				</HashRouter>

			</div>

			<div className="mailerglue-bottom">
				<a href="#" className="mailerglue-link-muted">Skip onboarding</a>
			</div>
		</>
	);

}

var rootElement = document.getElementById( 'mailerglue-onboarding' );

if ( rootElement ) {
	render( <Onboarding />, rootElement );
}