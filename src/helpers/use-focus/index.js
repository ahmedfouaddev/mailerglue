import {useRef} from '@wordpress/element';

export const useFocus = () => {
	const htmlElRef = useRef(null)
	const setFocus = () => {htmlElRef.current &&  htmlElRef.current.focus()}

	return [htmlElRef, setFocus]
}