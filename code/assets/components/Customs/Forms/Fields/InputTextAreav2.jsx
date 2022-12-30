import React from "react";
import { useController } from "react-hook-form";

//resize none
const styles = {
  resize: "none",
};

function InputTextAreav2(props) {
  const { field } = useController(props);

  return (
    <>
      <label className="block text-sm font-medium mb-1">{props.label}</label>
      <textarea
        style={styles}
        className="form-input w-full"
        placeholder={props.placeholder}
        disabled={props.disabled}
        rows="15"
        {...field}
      />
    </>
  );
}

export default InputTextAreav2;
