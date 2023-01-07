import { __ } from '@wordpress/i18n';
import { render, Component, Fragment, useState, useCallback, useEffect, useRef } from '@wordpress/element';
import { useFocus } from '@helpers/use-focus';

import {
	__experimentalSpacer as Spacer,
	__experimentalHStack as HStack,
	__experimentalText as Text,
	Flex,
	FlexItem,
	FlexBlock,
} from '@wordpress/components';

import { theme } from '@data/theme';

import apiFetch from '@wordpress/api-fetch';

const ListTitle = props => {

	const { attributes, setAttributes } = props;

	const { list } = mailerglue_backend;

	const { sizes, fontweight, colors } = theme;

	return (
		<FlexItem style={{ flexBasis: '33%', backgroundColor: colors.bg.gray1, display: "flex", alignItems: "center", padding: '0 0 0 32px' }}>
			<h2>{ attributes.title }</h2>
		</FlexItem>
	);

}

export default ListTitle;