import {
	isEqual
} from 'includes/utility';

const { __ } = wp.i18n;

const {
	debounce
} = lodash;

const {
	addQueryArgs
} = wp.url;

const apiFetch = wp.apiFetch;

const {
	Component,
	RawHTML
} = wp.element;

const {
	Placeholder,
	Spinner
} = wp.components;

export default class TemplateRender extends Component {
	constructor(props) {
		super(props);

		this.state = {
			response: null,
		};
	}

	componentDidMount() {
		this.isStillMounted = true;
		this.fetch();
		// Only debounce once the initial fetch occurs to ensure that the first
		// renders show data as soon as possible.
		this.fetch = debounce(this.fetch, 500);
	}

	componentWillUnmount() {
		this.isStillMounted = false;
	}

	componentDidUpdate(prevProps) {
		if (!isEqual(prevProps, this.props)) {
			this.fetch();
		}
	}

	fetch() {
		if (!this.isStillMounted)
			return;

		if (null !== this.state.response)
			this.setState({ response: null });

		const path = this.rendererPath();
		const {
			onSuccess = () => { },
			onError = () => { }
		} = this.props

		// Store the latest fetch request so that when we process it, we can
		// check if it is the current request, to avoid race conditions on slow networks.
		const fetchRequest = (this.currentFetchRequest = apiFetch({ path })
			.then((response) => {
				if (
					this.isStillMounted &&
					fetchRequest === this.currentFetchRequest &&
					response
				) {
					this.setState({ response: response.rendered });
					setTimeout(onSuccess(window.ReactDOM.findDOMNode(this)), 100);
				}
			})
			.catch((error) => {
				if (
					this.isStillMounted &&
					fetchRequest === this.currentFetchRequest
				) {
					this.setState({
						response: {
							error: true,
							errorMsg: error.message,
						},
					});
					onError();
				}
			}));

		return fetchRequest;
	}

	rendererPath() {
		const {
			block,
			attributes = null,
		} = this.props;

		return addQueryArgs(`/wp/v2/block-renderer/${block}`, {
			context: 'edit',
			...(null !== attributes ? { attributes } : {}),
		});
	}

	EmptyResponsePlaceholder() {
		return (
			<Placeholder>
				{__('Block rendered as empty.')}
			</Placeholder>
		)
	}

	ErrorResponsePlaceholder(response) {
		const errorMessage = sprintf(
			// translators: %s: error message describing the problem
			__('Error loading block: %s'),
			response.errorMsg
		);
		return (
			<Placeholder>{errorMessage}</Placeholder>
		);
	}

	LoadingResponsePlaceholder() {
		return (
			<Placeholder>
				<Spinner />
			</Placeholder>
		);
	}

	render() {
		const response = this.state.response;

		const {
			EmptyResponsePlaceholder,
			ErrorResponsePlaceholder,
			LoadingResponsePlaceholder
		} = this;

		if (response === '') {
			return (
				<EmptyResponsePlaceholder />
			);
		} else if (!response) {
			return (
				<LoadingResponsePlaceholder />
			);
		} else if (response.error) {
			return (
				<ErrorResponsePlaceholder
					response={response}
				/>
			);
		}

		return (
			<RawHTML key="html">
				{response}
			</RawHTML>
		);
	}
}