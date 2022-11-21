import { __ } from '@wordpress/i18n';

import apiFetch from '@wordpress/api-fetch';

import { render, Component, Fragment, useState, useCallback, useEffect, useRef } from '@wordpress/element';

import {
	__experimentalHeading as Heading,
	__experimentalDivider as Divider,
	__experimentalSpacer as Spacer,
	__experimentalInputControl as InputControl,
	PanelBody,
	PanelRow,
	Button,
	Notice,
	ExternalLink
} from '@wordpress/components';

import { withRouter } from 'react-router';

const OnboardingConnect = props => {

	const {state, setState} = props;
	const {admin_first_name, api_url} = mailerglue_backend;

	const signInRequest = (e) => {

		setState( prevValues => {
			return { ...prevValues, sending: true }
		} );

		apiFetch( {
			path: api_url + '/verify_login',
			method: 'post',
			data: {
				email: state.email,
				password: state.password,
			},
		} ).then(
			(response) => {
				console.log(response);
				if ( ! response.success ) {
					setState( prevValues => {
						return { ...prevValues, sending: false, errors: { login: response.message }, access_token: '' }
					} );
				} else {
					setState( prevValues => {
						return { ...prevValues, sending: false, errors: { login: '' }, access_token: response, from_name: response.name, from_email: response.email }
					} );

					props.history.push('/settings');
				}
			},

			(error) => {
				setState( prevValues => {
					return { ...prevValues, sending: false, errors: { login: error.message }, access_token: '' }
				} );
			}
		);

	};

	useEffect(() => {

	}, []);

	return (
		<>

		{ admin_first_name ? 
			<Heading level={5} className="mailerglue-text-regular">Welcome, {admin_first_name}!</Heading> : 
			<Heading level={5} className="mailerglue-text-regular">Welcome!</Heading>
		}

		<Heading level={2}>Let's begin by connecting your Mailer Glue account</Heading>

		<p className="mailerglue-text-bigger">
			Don't have an account yet? <ExternalLink href="#">Sign up</ExternalLink>
		</p>

		<Spacer paddingTop={10} marginBottom={0} />

		{ state.errors.login &&
		<Notice status="error" isDismissible={false}>
			<p>{ state.errors.login }</p>
		</Notice> }

		<form action="/">
		<PanelBody className="mailerglue-panelbody-form">
			<PanelRow>
				<InputControl
					autoFocus
					placeholder={ __( 'Email address', 'mailerglue' ) }
					value={ state.email }
					onChange={
						( value ) => {
							setState( prevValues => {
								return { ...prevValues, email: value }
							} );
						}
					}
				/>
			</PanelRow>
			<PanelRow>
				<InputControl
					placeholder={ __( 'Password', 'mailerglue' ) }
					type="password"
					value={ state.password }
					onChange={
						( value ) => {
							setState( prevValues => {
								return { ...prevValues, password: value }
							} );
						}
					}
				/>
			</PanelRow>
			<Spacer paddingTop={3} marginBottom={0} />
			<PanelRow>
				<Button
					isPrimary
					type="submit"
					disabled={ ! state.email || ! state.password || state.sending }
					isBusy={ state.sending }
					onClick={ signInRequest }
					>
					{ __( 'Connect your Mailer Glue account', 'mailerglue' ) }
				</Button>
			</PanelRow>
		</PanelBody>
		</form>

		</>
	);

}

export default withRouter(OnboardingConnect);