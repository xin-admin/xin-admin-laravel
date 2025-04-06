import {
  Checkbox,
  ColorPicker,
  DatePicker,
  Form,
  Input,
  InputNumber,
  Radio,
  Rate,
  Select,
  Slider,
  Switch,
  TimePicker,
} from 'antd';

export default (props: { item: any }) => {
  const { item } = props;
  return (
    <Form.Item name={item.key} style={{ marginBottom: 0 }}>
      {item.type === 'input' && <Input {...item.props} />}
      {item.type === 'password' && <Input.Password {...item.props} />}
      {item.type === 'textarea' && <Input.TextArea {...item.props} />}
      {item.type === 'checkout' && <Checkbox.Group {...item.props} options={item.options} />}
      {item.type === 'color' && <ColorPicker {...item.props} defaultValue="#1677ff" showText />}
      {item.type === 'date' && <DatePicker {...item.props} />}
      {item.type === 'number' && <InputNumber {...item.props} min={1} max={10} defaultValue={3} />}
      {item.type === 'radio' && <Radio.Group {...item.props} options={item.options} />}
      {item.type === 'rate' && <Rate {...item.props} allowHalf defaultValue={2.5} />}
      {item.type === 'select' && <Select {...item.props} options={item.options} />}
      {item.type === 'slider' && <Slider {...item.props} />}
      {item.type === 'switch' && <Switch {...item.props} />}
      {item.type === 'time' && <TimePicker {...item.props} />}
    </Form.Item>
  );

}
