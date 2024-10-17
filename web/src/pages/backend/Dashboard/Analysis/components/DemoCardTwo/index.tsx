import {Card, Radio} from "antd";
import DemoPie from "../Carts/DemoPie";
import {FormattedMessage} from "@umijs/max";


export default () => {
  return (
    <Card
      title={<FormattedMessage id={'analysis.title6'}/>}
      extra={
        <>
          <Radio.Group defaultValue="b">
            <Radio.Button value="b"><FormattedMessage id={'analysis.button5'}/></Radio.Button>
            <Radio.Button value="c"><FormattedMessage id={'analysis.button6'}/></Radio.Button>
            <Radio.Button value="d"><FormattedMessage id={'analysis.button7'}/></Radio.Button>
          </Radio.Group>
        </>
      }
    >
      <div style={{height: 400}}>
        <FormattedMessage id={'analysis.tab1'}/>
        <DemoPie></DemoPie>
      </div>
    </Card>

  )
}
