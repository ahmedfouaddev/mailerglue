import { __ } from '@wordpress/i18n';

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
import { Link } from 'react-router-dom';

import apiFetch from '@wordpress/api-fetch';

import { useFocus } from '@helpers/use-focus';

const Signup = props => {

	const { state, setState } = props;

	const { api_url, words } = mailerglue_backend;

	const [ emailInputRef, setEmailInputRef ] = useFocus();

	const doAPIRequest = (e) => {

		setState( prevValues => {
			return { ...prevValues, sending: true }
		} );

		apiFetch( {
			path: api_url + '/signup',
			method: 'post',
			data: {
				email: state.email,
				password: state.password,
			},
		} ).then(
			(response) => {
				if ( ! response.success ) {
					setState( prevValues => {
						return { ...prevValues, sending: false, errors: { signup: response.message }, access_token: '', password: '' }
					} );

					setEmailInputRef();
				} else {
					setState( prevValues => {
						return { ...prevValues, sending: false, errors: { signup: '' } }
					} );

					//props.history.push('/settings');
				}
			},

			(error) => {
				setState( prevValues => {
					return { ...prevValues, sending: false, errors: { signup: error.message }, password: '' }
				} );
			}
		);

	};

	useEffect(() => {
		setEmailInputRef();
	}, []);

	return (
		<>

		<Heading level={2}>{ words.signup_heading }</Heading>

		<p className="mailerglue-text-bigger">
			{ words.signup_text }
		</p>

		<Spacer paddingTop={10} marginBottom={0} />

		{ state.errors.signup &&
		<Notice status="error" isDismissible={false}>
			<p>{ state.errors.signup }</p>
		</Notice> }

		<form action="/">
		<PanelBody className="mailerglue-panelbody-form">
			<PanelRow>
				<InputControl
					placeholder={ words.email_label }
					value={ state.email }
					onChange={
						( value ) => {
							setState( prevValues => {
								return { ...prevValues, email: value }
							} );
						}
					}
					ref={emailInputRef}
				/>
			</PanelRow>
			<PanelRow>
				<InputControl
					placeholder={ words.password_label }
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
					onClick={ doAPIRequest }
					>
					{ words.signup }
				</Button>
			</PanelRow>
		</PanelBody>
		</form>

		</>
	);

}

export default withRouter(Signup);