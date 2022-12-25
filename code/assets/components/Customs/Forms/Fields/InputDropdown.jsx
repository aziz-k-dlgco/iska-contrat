import React, { useState, useRef, useEffect } from "react";

function InputDropdown({ name, label, data, onChange, isAnother, disabled }) {
  const [selected, setSelected] = useState(null);
  const [otherValue, setOtherValue] = useState("");
  const inputRef = useRef(null);

  useEffect(() => {
    if (data.length > 0) {
      setSelected(data[0].value);
    }
  }, [data]);

  useEffect(() => {
    if (selected === "other" && inputRef.current) {
      inputRef.current.focus();
    }
  }, [selected]);

  function handleChange(event) {
    const { value } = event.target;
    setSelected(value);
    if (value === "other") {
      setOtherValue("");
    } else {
      onChange(event);
    }
  }

  function handleOtherChange(event) {
    setOtherValue(event.target.value);
    onChange(event);
  }

  let options = data;
  if (isAnother) {
    options = [...data, { value: "other", label: "Autre" }];
  }

  return (
    <>
      <label className="block text-sm font-medium mb-1" htmlFor={name}>
        {label}
      </label>
      <div className="flex items-center">
        <select
          id={name}
          className={`form-select w-full inline-block mr-2 ${
            disabled ? "bg-gray-200" : ""
          }`}
          onChange={handleChange}
          value={selected}
          disabled={disabled}
        >
          {options.map((item, index) => (
            <option key={index} value={item.value}>
              {item.label}
            </option>
          ))}
        </select>

        {selected === "other" && (
          <input
            ref={inputRef}
            type="text"
            value={otherValue}
            className="form-input inline-block"
            onChange={handleOtherChange}
          />
        )}
      </div>
    </>
  );
}

export default InputDropdown;
