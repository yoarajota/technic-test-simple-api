import translations from "../extra/translations"

export function makeRequest(string: string) {
  return import.meta.env.VITE_SERVER_URL + string;
}

export function bootstrapTableTranslateFields(
  fields: Array<{ [key: string]: string }>
) {
  return fields.map((field) => ({
    ...field,
    key: field.name,
    name: field.label,
  }));
}


export function findSelectColor(data: { [key: string]: any }) {
  const color = data.field?.options.find(
    (option: { [key: string]: any }) =>
      option.value === data.item["real_" + data.field.key]
  )?.class;

  let classCss = "";
  if (color) {
    classCss = color + ' with-color';
  }

  return classCss;
}

export function isRequired(validations: Array<{ [key: string]: any }>) {
  return !!validations.find(
    (validation: { [key: string]: any }) => validation.type === "required"
  );
}


export function fixHeader(string: string) {
  if (!string) {
    return "";
  }

  return translate(string.charAt(0).toUpperCase() + string.slice(1));
}

export function translate(string: string) {
  return translations?.[string] ?? string;
}
