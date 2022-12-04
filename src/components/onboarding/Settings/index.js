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
} from '@wordpress/components';

import { withRouter } from 'react-router';

import { useFocus } from '@helpers/use-focus';

const Settings = props => {

	const { state, setState, updateState } = props;

	const { api_url, words } = mailerglue_backend;

	const saveBasicSettings = (e) => {

		setState( prevValues => {
			return { ...prevValues, sending: true }
		} );

		apiFetch( {
			path: api_url + '/save_basic_settings',
			method: 'post',
			data: {
				from_name: state.from_name,
				from_email: state.from_email,
			},
			headers: {
				'MailerGlue-Access-Token' : state.access_token.token,
				'MailerGlue-Account-ID' : state.access_token.id,
			},
		} ).then(
			(response) => {
				if ( ! response.success ) {
					setState( prevValues => {
						return { ...prevValues, sending: false, errors: { settings: response.message } }
					} );
				} else {
					setState( prevValues => {
						return { ...prevValues, sending: false, errors: { settings: '' } }
					} );
					props.history.push('/personalize');
				}
			},

			(error) => {
				setState( prevValues => {
					return { ...prevValues, sending: false, errors: { settings: error.message } }
				} );
			}
		);

	};

	useEffect(() => {
		console.log( state );
	}, []);

	return (
		<>

		<Heading level={2}>Set your default newsletter settings</Heading>

		<p className="mailerglue-text-bigger">
			You can easily set different newsletter settings when publishing a newsletter or change your newsletter defaults in the Settings.
		</p>

		<Spacer paddingTop={10} marginBottom={0} />

		{ state.errors.settings &&
		<Notice status="error" isDismissible={false}>
			<p>{ state.errors.settings }</p>
		</Notice> }

		<form action="/">
		<PanelBody className="mailerglue-panelbody-form">
			<PanelRow>
				<InputControl
					label="From name"
					placeholder="ABC Productions, Inc."
					value={ state.from_name }
					onChange={
						( value ) => {
							setState( prevValues => {
								return { ...prevValues, from_name: value }
							} );
						}
					}
				/>
			</PanelRow>
			<p className="components-base-control__help">Your subscribers will see this name in their inboxes.</p>
			<PanelRow>
				<InputControl
					label="From email"
					type="email"
					placeholder="name@domain.com"
					value={ state.from_email }
					required
					onChange={
						( value ) => {
							setState( prevValues => {
								return { ...prevValues, from_email: value }
							} );
						}
					}
				/>
			</PanelRow>
			<p className="components-base-control__help">Subscribers will see and reply to this email address.</p>
			<Spacer paddingTop={3} marginBottom={0} />
			<PanelRow>
				<Button
					isPrimary
					type="submit"
					disabled={ ! state.from_name || ! state.from_email || state.sending }
					isBusy={ state.sending }
					onClick={ saveBasicSettings }
					>
					Save & continue
				</Button>
			</PanelRow>
		</PanelBody>
		</form>

		</>
	);

}

export default withRouter(Settings);