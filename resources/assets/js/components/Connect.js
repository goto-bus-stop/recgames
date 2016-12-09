import { Component, h } from 'preact'

export default class Connect extends Component {
  constructor (props) {
    super(props)
    this.setState(props.store.getState())
  }

  componentDidMount () {
    this.unsubscribe = this.props.store.subscribe(() => {
      this.setState(this.props.store.getState())
    })
  }

  componentWillUnmount () {
    this.unsubscribe()
  }

  render () {
    return <this.props.component {...this.state} />
  }
}
